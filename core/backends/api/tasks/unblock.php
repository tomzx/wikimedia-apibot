<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Unblock User.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_changing.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/unblock.php' );



class API_Task_Unblock extends API_Task_Changing
{

	# ----- Implemented ----- #

	protected function api_action ()
	{
		return new API_Action_Unblock ( $this->backend );
	}


	protected function action_rights ()
	{
		return array ( "unblock" );
	}


	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['user'] = $this->resolve_user_name ( $params['user'] ) )
			=== NULL )
			return false;

		if ( $this->simulation ( "Would unblock user " .
			$params['user'], $params ) )
			return true;

		$logbeg = "User " . $params['user'];
		$actiondesc = "unblocked";

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


}
