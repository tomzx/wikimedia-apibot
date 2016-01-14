<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Fetch page by pageid.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_fetch_editable_page.php' );



class API_Task_FetchPageid extends API_Task_GenericFetchEditablePage
{

	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $pageid = $this->resolve_pageid ( $params['pageid'] ) )
			=== NULL )
			return false;

		unset ( $params['pageid'] );

		$logbeg = "Pageid " . $pageid .
			( is_null ( $params['revid'] ) ? "" : ", revid " . $params['revid'] );

		$params['_pageset'] = array ( 'pageids' => $pageid );

		return $this->fetch_editable_page ( $logbeg, $params );
	}


}
