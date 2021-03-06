<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Task: Unblock User.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_changing.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/unblock.php' );



class Web_Task_Unblock extends Web_Task_Changing
{

	# ----- Implemented ----- #

	protected function action ()
	{
		return new Web_Action_Unblock ( $this->backend );
	}


	# ----- Task support ----- #

	protected function check_result ( $data, $logbeg, $actdesc )
	{
		if ( preg_match ( '/\<div class\=\"error\"\>(.+)\<\/div\>/u', $data, $matches ) )
		{
			$error_text = preg_replace ( '/\<[^\>]+\>/u', "", $matches[1] );
			$this->log ( "Error: Wiki said: " . $error_text, LL_ERROR );
			return false;
		}
		else
		{
			return true;
		}
	}


	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['user'] = $this->resolve_user_name ( $params['user'] ) )
			=== NULL )
			return false;

		if ( $this->simulation ( "Would unblock user '$user'", $params ) )
			return true;

		$logbeg = "User " . $params['user'];
		$actiondesc = "unblocked";

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


}
