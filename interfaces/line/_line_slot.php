<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Line element root class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/signal.php' );
require_once ( dirname ( __FILE__ ) . '/../../core/common/exceptions.php' );



class ApibotException_CannotRecognizeCallback extends ApibotException_TaskClose
{
}


class ApibotException_UnknownLineSignal extends ApibotException_TaskClose
{
}


class ApibotException_ProcessingLineBroken extends ApibotException_TaskClose
{
}


class ApibotException_BadCallback extends ApibotException_TaskClose
{
}



abstract class Line_Slot
{

	protected $core;

	protected $callbacks = array();
	protected $backcalls = array();

	protected $old_params = array();

	protected $jobdata = NULL;

	protected $last_signal_id = PHP_INT_MAX;


	public $explicit_name = "";

	public $default_data_key = "*";

	public $set_slot_params_from_signal = true;
	public $preserve_slot_signal_params = NULL;


	# ----- Constructor ----- #


	function __construct ( $core )
	{
		$this->core = $core;
	}


	# ----- Tools ----- #


	protected function log ( $message, $loglevel = LL_INFO, $preface = "" )
	{
		
		if ( isset ( $this->core ) )
			return $this->core->log ( $this->object_name() . ": " . $message,
				$loglevel, $preface );
	}


	protected function object_name ()
	{
		return $this->signal_log_slot_name() .
			( empty ( $this->explicit_name ) ? "" : "." . $this->explicit_name );
	}


	# ----- Linking ----- #


	public function is_linked ()
	{
		return ( ! empty ( $this->callbacks ) );
	}


	public function linked_with ()
	{
		return $this->callbacks;
	}


	public function link_with ( $callback, $backcall = true )
	{
		if ( $callback instanceof Line_Slot )
		{
			$this->callbacks[] = $callback;
			if ( $backcall )
				$callback->link_to ( $this, false );
		}
		else
		{
			throw new ApibotException_BadCallback (
				'notanobject',
				"The callback you try to link with is not a caller object" );
		}
	}


	public function link_to ( $callback_caller, $backcall = true )
	{
		if ( $callback_caller instanceof Line_Slot )
		{
			$this->backcalls[] = $callback_caller;
			if ( $backcall )
				$callback_caller->link_with ( $this, false );
		}
		else
		{
			throw new ApibotException_BadCallback (
				'notanobject',
				"The callback you try to link to is not a caller object" );
		}
	}


	public function unlink ( $callback, $backcall = true )
	{
		foreach ( $this->callbacks as $key => $cb )
			if ( $callback == $cb )
			{
				if ( $backcall && $cb instanceof Line_Slot )
					$cb->unlink_from ( $this, false );
				unset ( $this->callbacks[$key] );
			}
	}


	public function unlink_from ( $callback, $backcall = true )
	{
		foreach ( $this->backcalls as $key => $bc )
			if ( $callback = $bc )
			{
				if ( $backcall )
					$bc->unlink ( $this, false );
				unset ( $this->backcalls[$key] );
			}
	}


	# ----- Signal support ----- #


	# --- Tools --- #


	protected function signal_log ( &$signal )
	{
		$signal->log_slot (
			$this->signal_log_slot_type(),
			$this->signal_log_slot_name(),
			$this->signal_log_job()
		);
	}


	protected function end_of_line_result ( &$signal )
	{
		$this->log ( $this->signal_log_slot_type() . " " .
			$this->signal_log_slot_name() . " (" . get_class ( $this ) .
			") is not connected to anything - broken line!", LL_ERROR );
		$this->log ( "  (Probably you forgot to add one at the line end. " .
			"  Why process data if you don't keep it after that?)", LL_DEBUG );
		throw new ApibotException_ProcessingLineBroken (
			'linebroken',
			"Line ends unexpectedly at " . $this->signal_log_slot_type() .
				" " . get_class ( $this )
		);
		return false;
	}


	# --- Signal types support --- #


	protected function process_start ( &$signal )  // override on need
	{
		return true;
	}


	protected function process_end ( &$signal )  // override on need
	{
		return true;
	}


	protected function process_data ( &$signal )  // override typically
	{
		return true;
	}


	protected function set_slot_params_from_signal ( &$signal )
	{
		if ( ! $this->set_slot_params_from_signal )
			return true;

		$slot_typegroup = $this->signal_log_slot_type();
		if ( $signal->exists_paramgroup ( $slot_typegroup ) )
			$this->set_params ( $signal->paramgroup ( $slot_typegroup ) );

		$slot_namegroup = $this->signal_log_slot_name();
		if ( $signal->exists_paramgroup ( $slot_namegroup ) )
			$this->set_params ( $signal->paramgroup ( $slot_namegroup ) );

		$slot_objectgroup = $this->signal_log_slot_name();
		if ( $signal->exists_paramgroup ( $slot_objectgroup ) )
			$this->set_params ( $signal->paramgroup ( $slot_objectgroup ) );

		if ( ! isset ( $this->preserve_slot_signal_params ) ||
			is_null ( $this->preserve_slot_signal_params ) )
		{
			$signal->unset_paramgroup ( $slot_typegroup );
			$signal->unset_paramgroup ( $slot_namegroup );
			$signal->unset_paramgroup ( $slot_objectgroup );
		}
		elseif ( $this->preserve_slot_signal_params === true )
			{} // just do not delete the paramgroups
		elseif ( is_string ( $this->preserve_slot_signal_params ) )
		{
			$signal->rename_paramgroup ( $slot_typegroup,
				"type." . $this->preserve_slot_signal_params );
			$signal->rename_paramgroup ( $slot_namegroup,
				"name." . $this->preserve_slot_signal_params );
			$signal->rename_paramgroup ( $slot_objectgroup,
				"object." . $this->preserve_slot_signal_params );
		}
	}


	protected function process_start_signal ( &$signal )
	{
		$this->push_params();
		$this->set_slot_params_from_signal ( $signal );
		return $this->process_start ( $signal );
	}


	protected function process_data_signal ( &$signal )
	{
		$this->push_params();

		$this->set_slot_params_from_signal ( $signal );

		$result = $this->process_data ( $signal );

		$this->pop_params();

		return $result;
	}


	protected function process_end_signal ( &$signal )
	{
		$this->set_slot_params_from_signal ( $signal );
		$result = $this->process_end ( $signal );
		$this->pop_params();
		return $result;
	}


	protected function process_signal ( &$signal )
	{
		$this->jobdata = NULL;

		$signal_class = get_class ( $signal );
		switch ( $signal_class )
		{
			case 'LineSignal_Start' :
				$result = $this->process_start_signal ( $signal );
				break;
			case 'LineSignal_Data' :
				$result = $this->process_data_signal ( $signal );
				break;
			case 'LineSignal_End' :
				$result = $this->process_end_signal ( $signal );
				break;
			default :
				$this->log ( "Unknown signal type detected (" .
					$signal_class . ") - should not occur!", LL_ERROR );
				throw new ApibotException_UnknownLineSignal (
					'unknownlinesignal',
					"Cannot recognize the signal type"
				);
		}
		$this->signal_log ( $signal );
		return $result;
	}


	protected function propagate_signal ( &$signal )
	{
		$total = false;
		foreach ( $this->callbacks as $callback )
		{
			$signal_clone = clone $signal;

			if ( is_object ( $callback ) && ( $callback instanceof Line_Slot ) )
			{
				$result = $callback->process ( $signal_clone );
			}
			elseif ( is_callable ( $callback ) )
			{
				$result = call_user_func ( $callback, $signal_clone );
			}
			else
			{
				$this->log ( "Cannot recognize a callback!", LL_ERROR );
				throw new ApibotException_CannotRecognizeCallback (
					'unknowncallback',
					"Cannot recognize the type of the specified callback"
				);
			}

			if ( is_null ( $result ) )
			{
				$total = NULL;
				break;
			}
			elseif ( $result )
			{
				$total = true;
			}

		}
		return $total;
	}


	# ----- Parameters support ----- #


	protected function _get_param ( &$params, $paramname )
	{
		if ( isset ( $this->$paramname ) )
			$params[$paramname] = $this->$paramname;
	}


	protected function _set_param ( &$params, $paramname )
	{
		if ( isset ( $params[$paramname] ) )
			$this->$paramname = $params[$paramname];
		else
			unset ( $this->$paramname );
	}


	protected function get_params ()
	{
		$params = array();
		$this->_get_param ( $params, 'default_data_key' );
		return $params;
	}


	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'default_data_key' );
	}


	protected function push_params ()
	{
		$this->old_params[] = $this->get_params();
	}


	protected function pop_params ()
	{
		$this->set_params ( array_pop ( $this->old_params ) );
	}


	# ----- Job data support ----- #


	protected function signal_log_job ()
	{
		return $this->jobdata;
	}


	protected function set_jobdata ( $result = NULL, $extra_params = array(),
		$exclude_params = array() )  // use in process_data() etc.
	{
		$params = array_merge ( $this->get_params(), $extra_params );
		foreach ( $exclude_params as $name )
			if ( isset ( $params[$name] ) )
				unset ( $params[$name] );

		$this->jobdata = array();

		if ( ! empty ( $params ) )
			$this->jobdata['params'] = $params;
		if ( ! is_null ( $result ) )
			$this->jobdata['result'] = $result;
	}


	# ----- Entry point ----- #

	public function process ( &$signal )
	{
		if ( $signal->id === $this->last_signal_id )
			return true;  // element was already processed (alternate line branches?)
		$this->last_signal_id = $signal->id;
		if ( ! $this->process_signal ( $signal ) )
			return false;
		if ( ! $this->is_linked() )
			return $this->end_of_line_result ( $signal );

		return $this->propagate_signal ( $signal );
	}


	# ----- Abstract ----- #

	abstract protected function signal_log_slot_type ();
	abstract protected function signal_log_slot_name ();


}
