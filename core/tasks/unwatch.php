<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Task: Unwatch page.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../backends/api/tasks/unwatch.php' );



class Task_Unwatch extends Task
{

	# ----- Overriding ----- #


	protected function api_task ()
	{
		return new API_Task_Unwatch ( $this->core->backend ( 'API' ) );
	}


	# ----- Implemented ----- #

	protected function task_name ()
	{
		return "unwatch";
	}


	protected function supported_backends ()
	{
		return array ( "api" );
	}


}
