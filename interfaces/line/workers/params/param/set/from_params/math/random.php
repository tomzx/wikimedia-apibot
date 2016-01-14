<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Param: Set: Math: Random class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



class Worker_SetParam_Math_Random extends Worker_SetParam_Math
{

	public $min = 0;
	public $max = PHP_INT_MAX;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Random";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'min' );
		$this->_get_param ( $params, 'max' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'min' );
		$this->_set_param ( $params, 'max' );

		return parent::set_params ( $params );
	}


	# ----- Implemented ----- #


	protected function modify_paramvalue ( $from_value )
	{
		$this->die_on_nonnumeric_property ( 'min' );
		$this->die_on_nonnumeric_property ( 'max' );

		return rand ( $this->min, $this->max );
	}


}
