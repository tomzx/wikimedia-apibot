<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal custom data element setter class - String - Extend
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_String_Extend extends Worker_DataElement_String
{


	public $prepend = "";  // preface this string to the original value
	public $append  = "";  // append this string after the original value


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Extend";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'prepend' );
		$this->_get_param ( $params, 'append' );

		return $params;
	}


	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'prepend' );
		$this->_set_param ( $params, 'append' );

		return parent::set_params ( $params );
	}


	# ----- Implemented ----- #


	protected function modify_element ( $element )
	{
		$this->die_on_nonstring_property ( 'prepend' );
		$this->die_on_nonstring_property ( 'append' );

		return $this->prepend . $element . $this->append;
	}


}
