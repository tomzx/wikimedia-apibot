<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic filter with checker class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../../../libs/misc/subelements.php' );


abstract class Filter_Sieving extends Filter
{


	protected $checker;  // the object that does the checking itself

	protected $sublevels = NULL;


	protected $rejects_callbacks = array();
	protected $rejects_backcalls = array();


	# ----- Constructor ----- #


	function __construct ( $core, $checker_params )
	{
		parent::__construct ( $core );
		$this->checker = $this->checker ( $checker_params );
	}


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		$preface = $this->slotname_preface();
		$postfix = $this->slotname_postfix();
		$jobname = $this->checker->job_name();

		return $preface .
			( empty ( $jobname )
				? ""
				: "." . $jobname ) .
			( empty ( $postfix )
				? ""
				: "." . $postfix );
	}


	public function process_data ( &$signal )
	{
		parent::process_data ( $signal );

		$result = $this->checker->check ( $this->element_to_check ( $signal ) );

		if ( is_null ( $result ) )
		{
			$this->log ( "Oops! I met a problem at " .
				$this->element_id_string ( $signal ), LL_ERROR );
		}
		elseif ( $result )
		{
			$this->log ( "Approved " . $this->element_id_string ( $signal ),
				LL_DEBUG );
		}
		else
		{
			$this->log ( "Rejected " . $this->element_id_string ( $signal ),
				LL_DEBUG );
			$this->rejects_propagate_signal ( $signal );
		}

		$this->set_jobdata ( $result );

		return $result;
	}


	# ----- Implemented ----- #


	protected function job_params ()
	{
		return $this->checker->params();
	}


	# ----- New ----- #


	protected function slotname_preface ()
	{
		return "Filter";
	}

	protected function slotname_postfix ()
	{
		return "";
	}


	protected function element_property ( $element, $sublevels )
	{
		return get_subelement ( $element, $sublevels );
	}


	protected function element_to_check ( &$signal )
	{
		$element = $signal->data_element ( $this->default_data_key );

		if ( isset ( $this->sublevels ) && ! is_null ( $this->sublevels ) )
			$element = $this->element_property ( $element, $this->sublevels );

		return $element;
	}


	# ----- Rejects servicing ----- #


	public function rejects_is_linked ()
	{
		return ( ! empty ( $this->rejects_callbacks ) );
	}


	public function rejects_linked_with ()
	{
		return $this->rejects_callbacks;
	}


	public function rejects_link_with ( $callback, $backcall = true )
	{
		if ( $callback instanceof Line_Slot )
		{
			$this->rejects_callbacks[] = $callback;
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


	public function rejects_link_to ( $callback_caller, $backcall = true )
	{
		if ( $callback_caller instanceof Line_Slot )
		{
			$this->rejects_backcalls[] = $callback_caller;
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


	public function rejects_unlink ( $callback, $backcall = true )
	{
		foreach ( $this->rejects_callbacks as $key => $cb )
			if ( $callback == $cb )
			{
				if ( $backcall && $cb instanceof Line_Slot )
					$cb->unlink_from ( $this, false );
				unset ( $this->rejects_callbacks[$key] );
			}
	}


	public function rejects_unlink_from ( $callback, $backcall = true )
	{
		foreach ( $this->rejects_backcalls as $key => $bc )
			if ( $callback = $bc )
			{
				if ( $backcall )
					$bc->unlink ( $this, false );
				unset ( $this->rejects_backcalls[$key] );
			}
	}


	protected function rejects_propagate_signal ( &$signal )
	{
		$total = false;
		foreach ( $this->rejects_callbacks as $callback )
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


	# ----- Abstract ----- #


	abstract protected function checker ( $checker_params );

	abstract protected function element_id_string ( &$signal );


}
