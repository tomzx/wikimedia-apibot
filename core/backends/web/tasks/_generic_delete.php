<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Task: Generic Delete Page.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_changing.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/delete.php' );



abstract class Web_Task_GenericDelete extends Web_Task_Changing
{

	# ----- Implemented ----- #

	protected function action ()
	{
		return new Web_Action_Delete ( $this->backend );
	}


	# ----- Entry point ----- #

	protected function delete_title_or_pageid ( $logbeg, $params, $setnames )
	{
		return $this->act_and_log ( $logbeg, "deleted", $params, $setnames );
	}


}
