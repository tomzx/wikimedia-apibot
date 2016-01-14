<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Create account.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_changing.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/createaccount.php' );



class API_Task_Createaccount extends API_Task_Changing
{

	# ----- Implemented ----- #

	protected function api_action ()
	{
		return new API_Action_Createaccount ( $this->backend );
	}


	protected function action_rights ()
	{
		return array ( "createaccount" );
	}


	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['name'] = $this->resolve_user_name ( $params['name'], 'name' ) )
			=== NULL )
			return false;

		if ( $this->simulation ( "Would create an account for user " .
			$params['name'], $params ) )
			return true;

		$logbeg = "Account '" . $params['name'] . "'";
		$actiondesc = "created";

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


}
