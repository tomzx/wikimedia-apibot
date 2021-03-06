<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Task: Fetch page by title.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_fetch_page.php' );
require_once ( dirname ( __FILE__ ) . '/../backends/api/tasks/fetch_title.php' );



class Task_FetchTitle extends Task_Fetch_Page
{

	# ----- Overriding ----- #


	protected function api_task ()
	{
		return new API_Task_FetchTitle ( $this->core->backend ( 'API' ) );
	}


	# ----- Implemented ----- #

	protected function task_name ()
	{
		return "fetch_title";
	}


	protected function supported_backends ()
	{
		return array ( "api" );
	}


}
