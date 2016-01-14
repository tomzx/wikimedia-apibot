<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Task: Fetch editable page by title.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_fetch_page.php' );



class Web_Task_FetchEditable extends Web_Task_FetchPage
{

	# ----- Entry points ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['title'] = $this->resolve_page_title ( $params['title'] ) )
			=== NULL )
			return false;

		$logbeg = "Page [[" . $params['title'] . "]]" .
			( isset ( $params['revid'] ) ? ", revid " . $params['revid'] : "" );

		return $this->fetch_editable_page ( $logbeg, $params );
	}


}
