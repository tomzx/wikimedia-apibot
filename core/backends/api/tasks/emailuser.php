<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Email User.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_changing.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/emailuser.php' );



class API_Task_Emailuser extends API_Task_Changing
{

	# ----- Implemented ----- #

	protected function api_action ()
	{
		return new API_Action_Emailuser ( $this->backend );
	}


	protected function action_rights ()
	{
		return array ( "emailuser" );
	}


	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['user'] = $this->resolve_user_name ( $params['user'] ) )
			=== NULL )
			return false;

		if ( $this->simulation ( "Would email user " . $params['user'] .
			" a message about " . $params['subject'], $params ) )
			return true;

		$logbeg = "User " . $params['user'];
		$actiondesc = "sent an email";

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


}
