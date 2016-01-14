<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Replacing interwikis Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_replace.php' );


class Worker_EditPage_ReplaceInterwikis extends Worker_EditPage_Replace
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Interwikis";
	}


	protected function is_task_single ( &$task )
	{
		return isset ( $task['old_wiki'] );
	}

	protected function perform_replace ( &$page, $task )
	{
		return $page->replace_interwiki (
			( ( $this->use_regexes )
				? $task['old_wiki']
				: preg_quote ( $task['old_wiki'] ) ),
			$task['new_wiki'],
			( isset ( $task['new_title'] )
				? $task['new_title']
				: NULL ) );
	}


}
