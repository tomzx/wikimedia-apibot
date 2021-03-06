<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal parameter group renamer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_Paramgroup_Rename extends Worker_Paramgroup
{

	public $new_group;


	# ----- Implemented ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Rename";
	}


	# ----- Overriding ----- #

	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$this->group = $this->group ( $signal );
		$this->new_group = $this->new_group ( $signal );

		$signal->rename_paramgroup ( $this->group, $this->new_group );

		$this->set_jobdata ( $result );

		return $result;
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'new_group' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'new_group' );

		return parent::set_params ( $params );
	}


	# ----- Overridable ----- #

	protected function new_group ( &$signal )
	{
		return $this->new_group;
	}


}
