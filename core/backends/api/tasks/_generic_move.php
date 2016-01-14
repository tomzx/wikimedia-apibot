<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Generic Move.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_changing.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/move.php' );



abstract class API_Task_GenericMove extends API_Task_Changing
{

	# ----- Implemented ----- #

	protected function api_action ()
	{
		return new API_Action_Move ( $this->backend );
	}


	protected function action_rights ()
	{
		return array ( "move" );
	}


	# ----- Entry point ----- #

	protected function move_title_or_pageid ( $logbeg, $params )
	{
		$actiondesc = "moved as [[" . $params['to'] . "]]";

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


}
