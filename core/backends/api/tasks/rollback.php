<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Rollback Page: API-based.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_changing.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/rollback.php' );




class API_Task_Rollback extends API_Task_Changing
{

	# ----- Implemented ----- #

	protected function api_action ()
	{
		return new API_Action_Rollback ( $this->backend );
	}


	protected function action_rights ()
	{
		return array ( "rollback" );
	}


	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['title'] = $this->resolve_page_title ( $params['title'] ) )
			=== NULL )
			return false;

		if ( $this->simulation ( "Would rollback page [[" .
			$params['title'] . "]] edits by user " . $params['user'],
			$params ) )
			return true;

		$logbeg = "Page [[" . $params['title'] . "]]";
		$actiondesc = "rollbacked";

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


}
