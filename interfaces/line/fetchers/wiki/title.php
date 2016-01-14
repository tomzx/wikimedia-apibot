<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Page (by title) fetcher class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_page.php' );
require_once ( dirname ( __FILE__ ) . '/../../../../core/tasks/fetch_title.php' );


class Fetcher_Wiki_Title extends Fetcher_Wiki_PageGeneric
{

	# ----- Overriding ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Title";
	}


	# ----- Instantiated ----- #

	protected function fetch_page ( &$signal, &$params )
	{
		$title = $signal->data_title ( $this->default_data_key );
		if ( is_null ( $title ) )
			return false;

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

		return $page;
	}


}
