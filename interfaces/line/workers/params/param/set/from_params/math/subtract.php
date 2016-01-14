<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Param: Set: Math: Subtract class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_SetParam_Math_Subtract extends Worker_SetParam_Math
{

	public $subtract;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Subtract";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'subtract' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'subtract' );

		return parent::set_params ( $params );
	}


	# ----- Implemented ----- #


	protected function modify_paramvalue ( $from_value )
	{
		$this->die_on_nonnumeric_property ( 'subtract' );

		return $from_value - $this->subtract;
	}


}
