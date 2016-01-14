<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Delete Page by Pageid.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_delete.php' );



class API_Task_DeletePageid extends API_Task_GenericDelete
{


	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['pageid'] = $this->resolve_pageid ( $params['pageid'] ) )
			=== NULL )
			return false;

		if ( $this->simulation ( "Would delete pageid " .
			$params['pageid'], $params ) )
			return true;

		$setnames = array (
			'watch' => array ( 'true' => "watch", 'false' => "unwatch" ),
		);

		$logbeg = "Pageid " . $params['pageid'];

		return $this->delete_title_or_pageid ( $logbeg, $params, $setnames );
	}


}
