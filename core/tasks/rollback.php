<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Task: Rollback page edits.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../backends/api/tasks/rollback.php' );



class Task_Rollback extends Task
{

	# ----- Overriding ----- #


	protected function api_task ()
	{
		return new API_Task_Rollback ( $this->core->backend ( 'API' ) );
	}


	# ----- Implemented ----- #

	protected function task_name ()
	{
		return "rollback";
	}


	protected function supported_backends ()
	{
		return array ( "api" );
	}


}
