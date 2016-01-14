<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Watch Page.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_watch.php' );


class API_Task_Watch extends API_Task_GenericWatch
{

	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		return $this->modify_watching (
			array_merge ( $params, array ( 'watch' => true ) )
		);
	}


}
