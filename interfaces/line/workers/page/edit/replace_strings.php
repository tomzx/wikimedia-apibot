<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Replace strings Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_replace.php' );


class Worker_EditPage_ReplaceStrings extends Worker_EditPage_Replace
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Strings";
	}


	protected function is_task_single ( &$task )
	{
		return isset ( $task['string'] );
	}

	protected function perform_replace ( &$page, $task )
	{
		return $page->replace_string (
			$task['string'],
			( isset ( $task['with'] ) ? $task['with'] : "" )
		);
	}


}
