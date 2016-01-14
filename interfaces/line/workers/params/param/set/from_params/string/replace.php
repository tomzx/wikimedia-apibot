<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Param: Set: Strings: Replace class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_SetParam_String_Replace extends Worker_SetParam_String
{


	public $match;       // a regex whose match will be replaced
	public $replacement; // the replacement that will be put instead
	public $count = -1;  // if not -1, replace only this number of times


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Replace";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'match' );
		$this->_get_param ( $params, 'replacement' );
		$this->_get_param ( $params, 'count' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'match' );
		$this->_set_param ( $params, 'replacement' );
		$this->_set_param ( $params, 'count' );

		return parent::set_params ( $params );
	}


	protected function modify_paramvalue ( $from_value )
	{
		$this->die_on_nonstring_property ( 'match' );
		$this->die_on_nonstring_property ( 'replacement' );
		$this->die_on_nonnumeric_property ( 'count' );

		return preg_replace ( $this->match, $this->replacement, $from_value,
			$this->count );
	}


}
