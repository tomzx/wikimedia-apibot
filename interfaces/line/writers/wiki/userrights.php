<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Change user rights Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Writer_Wiki_ChangeUserrights extends Writer_Wiki_Generic
{

	public $reason        = NULL;
	public $add_groups    = NULL;
	public $remove_groups = NULL;


	# ----- Instantiated ----- #

	protected function signal_log_slot_name ()
	{
		return "ChangeUserrights";
	}


	protected function task_paramnames ()
	{
		return array_merge (
			parent::task_paramnames(),
			array (
				"reason",
				"add_groups",
				"remove_groups",
			)
		);
	}


	# ----- Overriding ----- #


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$params = $this->get_task_params();

		$params['user'] = $signal->data_user ( $this->default_data_key );
		if ( is_null ( $params['user'] ) )
			return false;

		require_once ( dirname ( __FILE__ ) .
			'/../../../../core/tasks//userrights.php' );

		$task = new Task_Userrights ( $this->core );
		$result = $task->go ( $params );

		$this->set_jobdata ( $result, array ( 'user' => $params['user'] ) );

		return $result;
	}


}
