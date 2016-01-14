<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Task: Edit (submit) page.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../backends/api/tasks/edit.php' );
require_once ( dirname ( __FILE__ ) . '/../backends/web/tasks/edit.php' );



class Task_Edit extends Task
{

	# ----- Overriding ----- #


	protected function api_task ()
	{
		return new API_Task_Edit ( $this->core->backend ( 'API' ) );
	}


	protected function web_task ()
	{
		return new Web_Task_Edit ( $this->core->backend ( 'Web' ) );
	}


	# ----- Implemented ----- #

	protected function task_name ()
	{
		return "edit";
	}


	protected function supported_backends ()
	{
		return array ( "api", "web" );
	}


}
