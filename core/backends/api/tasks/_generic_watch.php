<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Generic Watch Page.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_changing.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/watch.php' );




abstract class API_Task_GenericWatch extends API_Task_Changing
{

	# ----- Implemented ----- #

	protected function api_action ()
	{
		return new API_Action_Watch ( $this->backend );
	}


	protected function action_rights ()
	{
		return array ( "watch" );
	}


	# ----- Entry points ----- #

	protected function modify_watching ( $params )
	{
		if ( ( $params['title'] = $this->resolve_page_title ( $params['title'] ) )
			=== NULL )
			return false;

		if ( $this->simulation ( "Would set page [[" . $params['title'] . "]] as " .
			( $params['watch'] ? "watched" : "not watched" ),
			$params ) )
			return true;

		if ( ! $params['watch'] )
			$params['unwatch'] = true;
		unset ( $params['watch'] );

		$logbeg = "Page [[" . $params['title'] . "]]";
		$actiondesc = "set as " . ( $params['watch'] ? "watched" : "not watched" );

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


}
