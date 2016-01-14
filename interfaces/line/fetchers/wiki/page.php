<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Page (via title, pageid or revid) fetcher class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_page.php' );
require_once ( dirname ( __FILE__ ) . '/../../../../core/tasks/fetch_editable.php' );
require_once ( dirname ( __FILE__ ) . '/../../../../core/tasks/fetch_title.php' );
require_once ( dirname ( __FILE__ ) . '/../../../../core/tasks/fetch_pageid.php' );
require_once ( dirname ( __FILE__ ) . '/../../../../core/tasks/fetch_revid.php' );


class Fetcher_Wiki_Page extends Fetcher_Wiki_PageGeneric
{

	// by now, signal_log_slot_name() is "Fetcher.Wiki.Page" - looks already good

	# ----- Instantiated ----- #

	protected function fetch_page ( &$signal, &$params )
	{
		$page = NULL;

		$title = $signal->data_title ( $this->default_data_key );

		if ( ! is_null ( $title ) )
		{
			$params['title'] = $title;
			if ( empty ( $params['properties'] ) )
			{
				$task = new Task_FetchEditable ( $this->core );
				$page = $task->go ( $params );
			}
			else
			{
				$task = new Task_FetchTitle ( $this->core );
				$page = $task->go ( $params );
			}
		}

		if ( ! isset ( $page ) || is_null ( $page ) )
		{
			$pageid = $signal->data_pageid ( $this->default_data_key );
			if ( ! is_null ( $pageid ) )
			{
			$params['pageid'] = $pageid;
				$task = new Task_FetchPageid ( $this->core );
				$page = $task->go ( $params );
			}
		}

		if ( ! isset ( $page ) || is_null ( $page ) )
		{
			$revid = $signal->data_revid ( $this->default_data_key );
			if ( ! is_null ( $revid ) )
			{
			$params['revid'] = $revid;
				$task = new Task_FetchRevid ( $this->core );
				$page = $task->go ( $params );
			}
		}

		return $page;
	}


}
