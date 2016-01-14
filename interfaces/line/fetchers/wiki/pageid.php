<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Page (via pageid) fetcher class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_page.php' );
require_once ( dirname ( __FILE__ ) . '/../../../../core/tasks/fetch_pageid.php' );


class Fetcher_Wiki_Pageid extends Fetcher_Wiki_PageGeneric
{

	# ----- Overriding ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Pageid";
	}


	# ----- Instantiated ----- #

	protected function fetch_page ( &$signal, &$params )
	{
		$pageid = $signal->data_pageid ( $this->default_data_key );
		if ( is_null ( $pageid ) )
			return false;

		$params['pageid'] = $pageid;

		$task = new Task_FetchPageid ( $this->core );
		$page = $task->go ( $params );

		return $page;
	}


}
