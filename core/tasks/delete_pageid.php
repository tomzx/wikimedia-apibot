<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Task: Delete page by pageid.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../backends/api/tasks/delete_pageid.php' );



class Task_DeletePageid extends Task
{

	# ----- Overriding ----- #


	protected function api_task ()
	{
		return new API_Task_DeletePageid ( $this->core->backend ( 'API' ) );
	}


	# ----- Implemented ----- #

	protected function task_name ()
	{
		return "delete_pageid";
	}


	protected function supported_backends ()
	{
		return array ( "api" );
	}


}
