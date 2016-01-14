<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal custom data element setter class - String - Substr
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_String_Substr extends Worker_DataElement_String
{


	public $start = 0;
	public $length;

	public $mb = true;


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


	# ----- Implemented ----- #


	protected function modify_element ( $element )
	{
		$this->die_on_nonnumeric_property ( 'start' );
		$this->die_on_nonnumeric_property ( 'length' );

		if ( $mb )
			if ( isset ( $this->length ) )
				return mb_substr ( $element, $this->start, $this->length );
			else
				return mb_substr ( $element, $this->start );
		else
			if ( isset ( $this->length ) )
				return substr ( $element, $this->start, $this->length );
			else
				return substr ( $element, $this->start );
	}


}
