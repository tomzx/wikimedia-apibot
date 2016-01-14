<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Set signal param class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Worker_SetParam extends Worker_Params
{


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Set";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'name' );
		$this->_get_param ( $params, 'group' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'name' );
		$this->_set_param ( $params, 'group' );

		return parent::set_params ( $params );
	}


	# ----- Implemented ----- #


	protected function new_param ( &$signal )
	{
		$value = $this->new_paramvalue ( $signal );
		if ( is_null ( $value ) )
			return NULL;

		return $signal->set_param ( $this->group, $this->name, $value );
	}


	# ----- New (Checks for mandatory worker properties) ----- #


	protected function die_on_unset_property ( $name )
	{
		if ( ! isset ( $this->$name ) )
		{
			$this->log ( '$' . $name . ' property is not set - cannot work!',
				LL_PANIC );
			die();
		}
	}


	protected function die_on_nonnumeric_property ( $name )
	{
		$this->die_on_unset_property ( $name );

		if ( ! is_numeric ( $this->$name ) )
		{
			$this->log ( '$' . $name . ' property is not numeric - cannot work!',
				LL_PANIC );
			die();
		}
	}


	protected function die_on_nonstring_property ( $name )
	{
		$this->die_on_unset_property ( $name );

		if ( ! is_string ( $this->$name ) )
		{
			$this->log ( '$' . $name . ' property is not string - cannot work!',
				LL_PANIC );
			die();
		}
	}


	# ----- Abstract ----- #


	abstract protected function new_paramvalue ( &$signal );


}
