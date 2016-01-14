<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Replace regexes Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_replace.php' );


class Worker_EditPage_ReplaceRegexes extends Worker_EditPage_Replace
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Regexes";
	}


	protected function is_task_single ( &$task )
	{
		return isset ( $task['regex'] );
	}

	protected function perform_replace ( &$page, $task )
	{
		return $page->replace_regex (
			$task['regex'],
			( isset ( $task['with' ] ) ? $task['with' ] : "" ),
			( isset ( $task['limit'] ) ? $task['limit'] : -1 )
		);
	}


}
