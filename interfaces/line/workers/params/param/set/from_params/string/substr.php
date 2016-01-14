<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Param: Set: Strings: Substring class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_SetParam_String_Substr extends Worker_SetParam_String
{


	public $mb = true;
	public $start = 0;
	public $length;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Substr";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'start' );
		$this->_get_param ( $params, 'length' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'start' );
		$this->_set_param ( $params, 'length' );

		return parent::set_params ( $params );
	}


	protected function modify_paramvalue ( $from_value )
	{
		$this->die_on_nonnumeric_property ( 'start' );
		$this->die_on_nonnumeric_property ( 'length' );

		if ( $this->mb )
			if ( isset ( $this->length ) )
				return mb_substr ( $from_value, $this->start, $this->length );
			else
				return mb_substr ( $from_value, $this->start );
		else
			if ( isset ( $this->length ) )
				return substr ( $from_value, $this->start, $this->length );
			else
				return substr ( $from_value, $this->start );
	}


}
