<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Param: Set: Math: Multiply class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_SetParam_Math_Multiply extends Worker_SetParam_Math
{

	public $multiply;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Multiply";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'multiply' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'multiply' );

		return parent::set_params ( $params );
	}


	# ----- Implemented ----- #


	protected function modify_paramvalue ( $from_value )
	{
		$this->die_on_nonnumeric_property ( 'multiply' );

		return $from_value * $this->multiply;
	}


}
