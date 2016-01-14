<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Move Page by Title.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_move.php' );



class API_Task_MoveTitle extends API_Task_GenericMove
{

	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['from'] = $this->resolve_page_title ( $params['from'], 'from' ) )
			=== NULL )
			return false;

		if ( $params['from'] == $params['to'] )
		{
			$this->log ( "Title [[" . $params['from'] .
				"]] will not be changed - skipping", LL_WARNING );
			return false;
		}

		if ( $this->simulation ( "Would move page [[" .
			$params['from'] . "]] as [[" . $params['to'] . "]]", $params ) )
			return true;

		$logbeg = "Page [[" . $params['from'] . "]]";

		return $this->move_title_or_pageid ( $logbeg, $params );
	}


}
