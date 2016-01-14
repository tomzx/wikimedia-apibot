<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Task: Upload file.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../backends/api/tasks/upload_file.php' );
require_once ( dirname ( __FILE__ ) . '/../backends/web/tasks/upload_file.php' );



class Task_UploadFile extends Task
{

	# ----- Overriding ----- #


	protected function api_task ()
	{
		return new API_Task_UploadFile ( $this->core->backend ( 'API' ) );
	}


	protected function web_task ()
	{
		return new Web_Task_UploadFile ( $this->core->backend ( 'Web' ) );
	}


	# ----- Implemented ----- #

	protected function task_name ()
	{
		return "upload_file";
	}


	protected function supported_backends ()
	{
		return array ( "api", "web" );
	}


}
