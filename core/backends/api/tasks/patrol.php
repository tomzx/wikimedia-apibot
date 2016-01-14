<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Patrol Recentchange.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_changing.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/patrol.php' );



class API_Task_Patrol extends API_Task_Changing
{

	# ----- Implemented ----- #

	protected function api_action ()
	{
		return new API_Action_Patrol ( $this->backend );
	}


	protected function action_rights ()
	{
		return array ( "patrol" );
	}


	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['rcid'] = $this->resolve_rcid ( $params['rcid'] ) )
			=== NULL )
			return false;

		if ( $this->simulation ( "Would patrol recentchange ID " .
			$params['rcid'], $params ) )
			return true;

		$logbeg = "Recentchange " . $params['rcid'];
		$actiondesc = "patrolled";

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


}
