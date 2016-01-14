<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Fetch editable page by title.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_fetch_editable_page.php' );



class API_Task_FetchEditable extends API_Task_GenericFetchEditablePage
{

	# ----- Entry points ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $title = $this->resolve_page_title ( $params['title'] ) )
			=== NULL )
			return false;
		unset ( $params['title'] );

		$logbeg = "Page [[" . $title . "]]" .
			( isset ( $params['revid'] ) ? ", revid " . $params['revid'] : "" );

		$params['_pageset'] = array ( 'titles' => $title );

		return $this->fetch_editable_page ( $logbeg, $params );
	}


}
