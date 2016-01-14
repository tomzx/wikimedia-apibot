<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Replace templates names Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_replace.php' );


class Worker_EditPage_ReplaceTemplatesNames extends Worker_EditPage_Replace
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Templates.Names";
	}


	protected function is_task_single ( &$task )
	{
		return isset ( $task['old_name'] );
	}

	protected function perform_replace ( &$page, $task )
	{
		return $page->replace_template_name (
			( ( $this->use_regexes )
				? $task['old_name']
				: preg_quote ( $task['old_name'] ) ),
			$task['new_name'],
			( isset ( $task['old_namespace'] ) ? $task['old_namespace'] : NULL ),
			( isset ( $task['new_namespace'] ) ? $task['new_namespace'] : NULL ),
			( isset ( $task['old_wiki'     ] ) ? $task['old_wiki'     ] : NULL ),
			( isset ( $task['new_wiki'     ] ) ? $task['new_wiki'     ] : NULL ),
			( isset ( $task['limit'] ) ? $task['limit'] : -1 ) );
	}


}
