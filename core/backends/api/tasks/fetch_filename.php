<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Fetch file by filename (ie. file page by title).
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_fetch_file.php' );



class API_Task_FetchFilename extends API_Task_GenericFetchFile
{

	# ----- Entry points ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $title = $this->resolve_page_title ( $params['title'] ) )
			=== NULL )
			return false;

		unset ( $params['title'] );

		$logbeg = "File [[" . $title . "]]";

		$params['_pageset'] = array ( 'titles' => $title );

		return $this->fetch_file ( $logbeg, $params );
	}


}
