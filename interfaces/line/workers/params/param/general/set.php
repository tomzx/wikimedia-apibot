<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Param: General: Set class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



class Worker_Param_Set extends Worker_Param_General
{


	public $value;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Set";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'value' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'value' );

		return parent::set_params ( $params );
	}


	# ----- Implemented ----- #


	protected function new_param ( &$signal )
	{
		if ( ! isset ( $this->value ) )
		{
			$this->log ( '$value property not set - cannot work!',
				LL_PANIC );
			die();
		}

		return $signal->set_param ( $this->group, $this->name, $this->value );
	}


}
