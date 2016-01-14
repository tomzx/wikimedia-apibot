<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal custom data element replacer class
#  (replaces a match in a string with the value from the signal data)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_String_ReplaceInto extends Worker_DataElement_String
{


	public $match;       // a regex whose match will be replaced
	public $into;        // the replacement that will be put instead
	public $count = -1;  // if not -1, replace only this number of times


	# ----- Implemented ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Replace";
	}


	# ----- Overriding ----- #


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'match' );
		$this->_get_param ( $params, 'into' );
		$this->_get_param ( $params, 'count' );

		return $params;
	}


	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'match' );
		$this->_set_param ( $params, 'into' );
		$this->_set_param ( $params, 'count' );

		return parent::set_params ( $params );
	}


	# ----- Implemented ----- #


	protected function modify_element ( $element )
	{
		$this->die_on_nonstring_property ( 'match' );
		$this->die_on_nonstring_property ( 'into' );
		$this->die_on_nonnumeric_property ( 'count' );

		if ( substr ( $this->match, 0, 1 ) == "/" )
			return str_replace ( $this->match, $element, $this->into, $this->count );
		else
			return preg_replace ( $this->match, $element, $this->into, $this->count );
	}


}
