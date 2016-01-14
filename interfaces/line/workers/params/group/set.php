<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal parameter group setter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_Paramgroup_Set extends Worker_Paramgroup
{

	public $group_array;


	# ----- Implemented ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Set";
	}


	# ----- Overriding ----- #

	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$old_value = $signal->paramgroup ( $this->group );

		$this->group = $this->group ( $signal );
		$this->group_array = $this->group_array ( $signal );

		$signal->set_paramgroup ( $this->group, $this->group_array );

		$extra_params = array (
			'old_value' => $old_value,
		);

		$this->set_jobdata ( $result, $extra_params );

		return $result;
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'group_array' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'group_array' );

		return parent::set_params ( $params );
	}


	# ----- Overridable ----- #

	protected function group_array ( &$signal )
	{
		return $this->group_array;
	}


}
