<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Param: Set: Strings: Repeat class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_SetParam_String_Repeat extends Worker_SetParam_String
{


	public $count;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Repeat";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'count' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'count' );

		return parent::set_params ( $params );
	}


	protected function modify_paramvalue ( $from_value )
	{
		$this->die_on_nonnumeric_property ( 'count' );

		return str_repeat ( $from_value, $this->count );
	}


}
