<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Task: Delete page by title.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../backends/api/tasks/delete_title.php' );
require_once ( dirname ( __FILE__ ) . '/../backends/web/tasks/delete_title.php' );



class Task_DeleteTitle extends Task
{

	# ----- Overriding ----- #


	protected function api_task ()
	{
		return new API_Task_DeleteTitle ( $this->core->backend ( 'API' ) );
	}


	protected function web_task ()
	{
		return new Web_Task_DeleteTitle ( $this->core->backend ( 'Web' ) );
	}


	# ----- Implemented ----- #

	protected function task_name ()
	{
		return "delete_title";
	}


	protected function supported_backends ()
	{
		return array ( "api", "web" );
	}


}
