<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Delete pageid Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_delete.php' );


class Writer_Wiki_DeletePageid extends Writer_Wiki_GenericDelete
{

	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Pageid";
	}


	# ----- Overriding ----- #


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$params = $this->get_task_params();

		$params['pageid'] = $signal->data_pageid ( $this->default_data_key );
		if ( is_null ( $params['pageid'] ) )
			return false;

		require_once ( dirname ( __FILE__ ) .
			'/../../../../core/tasks//delete_pageid.php' );

		$task = new Task_DeletePageid ( $this->core );
		$result = $task->go ( $params );

		$this->set_jobdata ( $result, array ( 'pageid' => $params['pageid'] ) );

		return $result;
	}


}
