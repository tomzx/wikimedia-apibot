<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Undo page Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Writer_Wiki_Undo extends Writer_Wiki_Generic
{

	public $summary    = NULL;

	public $undo_revid = NULL;
	public $to_revid   = NULL;

	public $is_bot     = NULL;
	public $is_minor   = NULL;
	public $watch      = NULL;


	# ----- Instantiated ----- #


	protected function signal_log_slot_name ()
	{
		return "Undo";
	}


	protected function task_paramnames ()
	{
		return array_merge (
			parent::task_paramnames(),
			array (
				"summary",
				"undo_revid",
				"to_revid",
				"is_bot",
				"is_minor",
				"watch",
			)
		);
	}


	# ----- Overriding ----- #


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$params = $this->get_task_params();

		$params['title'] = $signal->data_title ( $this->default_data_key );
		if ( is_null ( $params['title'] ) )
			return false;

		require_once ( dirname ( __FILE__ ) .
			'/../../../../core/tasks//undo.php' );

		$task = new Task_Undo ( $this->core );
		$result = $task->go ( $params );

		$this->set_jobdata ( $result, array ( 'title' => $params['title'] ) );

		return $result;
	}


}
