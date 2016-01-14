<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal custom data element setter class - String - Pad
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_String_Pad extends Worker_DataElement_String
{


	public $length;
	public $with = " ";
	public $type = STR_PAD_RIGHT;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Pad";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'length' );
		$this->_get_param ( $params, 'with' );
		$this->_get_param ( $params, 'type' );

		return $params;
	}


	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'length' );
		$this->_set_param ( $params, 'with' );
		$this->_set_param ( $params, 'type' );

		return parent::set_params ( $params );
	}


	# ----- Implemented ----- #


	protected function modify_element ( $element )
	{
		$this->die_on_nonnumeric_property ( 'length' );
		$this->die_on_nonstring_property ( 'with' );

		return str_pad ( $element, $this->length, $this->with, $this->type );
	}


}
