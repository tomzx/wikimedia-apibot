<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Replacing categories Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_replace.php' );


class Worker_EditPage_ReplaceCategories extends Worker_EditPage_Replace
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Categories";
	}


	protected function is_task_single ( &$task )
	{
		return isset ( $task['old_name'] );
	}

	protected function perform_replace ( &$page, $task )
	{
		return $page->replace_category (
			( ( $this->use_regexes )
				? $task['old_name']
				: preg_quote ( $task['old_name'] ) ),
			$task['new_name'],
			( isset ( $task['new_sortkey'] )
				? $task['new_sortkey']
				: NULL ) );
	}


}
