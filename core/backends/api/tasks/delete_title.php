<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task:  Delete Page by Title.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_delete.php' );



class API_Task_DeleteTitle extends API_Task_GenericDelete
{

	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['title'] = $this->resolve_page_title ( $params['title'] ) )
			=== NULL )
			return false;

		if ( $this->simulation ( "Would delete page [[" .
			$params['title'] . "]]", $params ) )
			return true;

		$setnames = array (
			'watch' => array ( 'true' => "watch", 'false' => "unwatch" ),
		);

		$logbeg = "Page [[" . $params['title'] . "]]";

		return $this->delete_title_or_pageid ( $logbeg, $params, $setnames );
	}


}
