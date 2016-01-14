<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Replace wikilinks Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_replace.php' );


class Worker_EditPage_ReplaceWikilinks extends Worker_EditPage_Replace
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Wikilinks";
	}


	protected function is_task_single ( &$task )
	{
		return isset ( $task['name'] );
	}

	protected function perform_replace ( &$page, $task )
	{
		if ( ! $this->use_regexes )
		{
			if ( isset ( $task['wiki'] ) )
				$task['wiki'] = preg_quote ( $task['wiki'] );
			if ( isset ( $task['namespace'] ) )
				$task['namespace'] = preg_quote ( $task['namespace'] );
			if ( isset ( $task['name'] ) )
				$task['name'] = preg_quote ( $task['name'] );
			if ( isset ( $task['anchor'] ) )
				$task['anchor'] = preg_quote ( $task['anchor'] );
			if ( isset ( $task['text'] ) )
				$task['text'] = preg_quote ( $task['text'] );
		}

		return $page->replace_wikilink (
			( isset ( $task['colon'] )
				? $task['colon']
				: NULL ),
			( isset ( $task['wiki'] )
				? $task['wiki']
				: NULL ),
			( isset ( $task['namespace'] )
				? $task['namespace']
				: NULL ),
			$task['name'],
			( isset ( $task['anchor'] )
				? $task['anchor']
				: NULL ),
			( isset ( $task['text'] )
				? $task['text']
				: NULL ),
			( isset ( $task['with' ] )
				? $task['with' ]
				: "" ),
			( isset ( $task['limit'] )
				? $task['limit']
				: -1 )
		);
	}


}