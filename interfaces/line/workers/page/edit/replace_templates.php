<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Replace templates Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_replace.php' );


class Worker_EditPage_ReplaceTemplates extends Worker_EditPage_Replace
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Templates";
	}


	protected function is_task_single ( &$task )
	{
		return isset ( $task['name'] );
	}

	protected function perform_replace ( &$page, $task )
	{
		return $page->replace_template (
			( isset ( $task['name'     ] )
				? ( ( $this->use_regexes )
					? $task['name']
					: preg_quote ( $task['name'] ) )
				: NULL ),
			( isset ( $task['namespace'] ) ? $task['namespace'] : NULL ),
			( isset ( $task['wiki'     ] ) ? $task['wiki'     ] : NULL ),
			( isset ( $task['with'     ] ) ? $task['with'     ] : "" ),
			( isset ( $task['limit'    ] ) ? $task['limit'    ] : -1 ) );
	}


}
