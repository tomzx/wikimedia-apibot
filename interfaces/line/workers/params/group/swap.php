<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal parameter group swapper class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_Paramgroup_Swap extends Worker_Paramgroup
{

	public $group2;


	# ----- Implemented ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Swap";
	}


	# ----- Overriding ----- #

	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$this->group = $this->group ( $signal );
		$this->group2 = $this->group2 ( $signal );

		$signal->swap_paramgroups ( $this->group, $this->group2 );

		$this->set_jobdata ( $result );

		return $result;
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'group2' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'group2' );

		return parent::set_params ( $params );
	}


	# ----- Overridable ----- #

	protected function group2 ( &$signal )
	{
		return $this->group2;
	}


}
