<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Task: Fetch file (page) by name.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_fetch_file.php' );
require_once ( dirname ( __FILE__ ) . '/../backends/api/tasks/fetch_filename.php' );



class Task_FetchFilename extends Task_Fetch_File
{

	# ----- Overriding ----- #


	protected function api_task ()
	{
		return new API_Task_FetchFilename ( $this->core->backend ( 'API' ) );
	}


	# ----- Implemented ----- #

	protected function task_name ()
	{
		return "fetch_filename";
	}


	protected function supported_backends ()
	{
		return array ( "api" );
	}


}
