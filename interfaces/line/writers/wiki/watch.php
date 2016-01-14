<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Watch page Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Writer_Wiki_WatchPage extends Writer_Wiki_Generic
{

	public $unwatch = NULL;


	# ----- Instantiated ----- #

	protected function signal_log_slot_name ()
	{
		return "WatchPage";
	}


	protected function task_paramnames ()
	{
		return array_merge (
			parent::task_paramnames(),
			array (
				"unwatch",
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
			'/../../../../core/tasks//watch.php' );

		$task = new Task_Watch ( $this->core );
		$result = $task->go ( $params );

		$this->set_jobdata ( $result, array ( 'title' => $params['title'] ) );

		return $result;
	}


}
