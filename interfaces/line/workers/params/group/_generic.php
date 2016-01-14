<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Set signal paramgroup class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Worker_Paramgroup extends Worker_Params
{

	public $group;


	# ----- Overriding ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Group";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'group' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'group' );

		return parent::set_params ( $params );
	}


	# ----- Overridable ----- #

	protected function group ( &$signal )
	{
		return $this->group;
	}


}
