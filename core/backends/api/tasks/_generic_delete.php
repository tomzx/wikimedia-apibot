<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Generic Delete Page.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_changing.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/delete.php' );



abstract class API_Task_GenericDelete extends API_Task_Changing
{

	# ----- Implemented ----- #

	protected function api_action ()
	{
		return new API_Action_Delete ( $this->backend );
	}


	protected function action_rights ()
	{
		return array ( "delete" );
	}


	# ----- Entry point ----- #

	protected function delete_title_or_pageid ( $logbeg, $params, $setnames )
	{
		return $this->act_and_log ( $logbeg, "deleted", $params, $setnames );
	}


}
