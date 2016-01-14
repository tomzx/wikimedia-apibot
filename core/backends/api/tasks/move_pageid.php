<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Move Page by Pageid.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_move.php' );



class API_Task_MovePageid extends API_Task_GenericMove
{

	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['fromid'] = $this->resolve_pageid ( $params['fromid'], 'fromid' ) )
			=== NULL )
			return false;

		if ( $this->simulation ( "Would move pageid " .
			$params['fromid'] . " as [[" . $params['to'] . "]]", $params ) )
			return true;

		$logbeg = "Pageid " . $params['fromid'];

		$setnames = array (
			'watch' => array ( 'true' => "watch", 'false' => "unwatch" ),
		);

		return $this->move_title_or_pageid ( $logbeg, $params, $setnames );
	}


}

