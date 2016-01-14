<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Param: Set: Strings: String position class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_SetParam_String_Pos extends Worker_SetParam_String
{


	public $mb = true;
	public $search;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Pos";
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


	protected function modify_paramvalue ( $from_value )
	{
		$this->die_on_nonstring_property ( 'search' );

		if ( $this->mb )
			return mb_strpos ( $from_value, $this->search );
		else
			return strpos ( $from_value, $this->search );
	}


}
