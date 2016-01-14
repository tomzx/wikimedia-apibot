<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Task: Change Userrights.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_changing.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/userrights.php' );



class Web_Task_Userrights extends Web_Task_Changing
{

	# ----- Implemented ----- #

	protected function action ()
	{
		return new Web_Action_Userrights ( $this->backend );
	}


	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['user'] = $this->resolve_user_name ( $params['user'] ) )
			=== NULL )
			return false;

		if ( $this->simulation ( "Would change the rights of user '$user'",
			$params ) )
			return true;

		$params['available'] = $params['add'];
		unset ( $params['add'] );
		$params['removable'] = $params['remove'];
		unset ( $params['remove'] );

		$logbeg = "User " . $params['user'] . " right(s)";
		$actiondesc = "changed";

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


}
