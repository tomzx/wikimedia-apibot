<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Param: Set: Strings: String length class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_SetParam_String_Length extends Worker_SetParam_String
{

	public $mb = true;  // multibyte or byte-wise length?
	public $encoding;   // if not set, will use the internal encoding


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Length";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'mb' );
		$this->_get_param ( $params, 'encoding' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'mb' );
		$this->_set_param ( $params, 'encoding' );

		return parent::set_params ( $params );
	}


	protected function modify_paramvalue ( $from_value )
	{
		if ( $this->mb )
			if ( isset ( $this->encoding ) )
				return mb_strlen ( $from_value, $this->encoding );
			else
				return mb_strlen ( $from_value );
		else
			return strlen ( $from_value );
	}


}
