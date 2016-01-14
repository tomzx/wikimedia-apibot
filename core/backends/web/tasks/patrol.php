<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Task: Patrol Recentchange.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_changing.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/patrol.php' );



class Web_Task_Patrol extends Web_Task_Changing
{

	# ----- Implemented ----- #

	protected function action ()
	{
		return new Web_Action_Patrol ( $this->backend );
	}


	# ----- Tools ----- #

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
		if ( ( $params['rcid'] = $this->resolve_rcid ( $params['rcid'] ) )
			=== NULL )
			return false;

		if ( $this->simulation ( "Would patrol recentchange ID '$rcid'", $params ) )
			return true;

		$logbeg = "Recentchange " . $params['rcid'];
		$actiondesc = "patrolled";

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


}
