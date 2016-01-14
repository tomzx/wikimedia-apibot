<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Replace templates paramnames Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_replace.php' );


class Worker_EditPage_ReplaceTemplatesParamnames extends Worker_EditPage_Replace
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Templates.Paramnames";
	}


	protected function is_task_single ( &$task )
	{
		return isset ( $task['name'] );
	}

	protected function perform_replace ( &$page, $task )
	{
		return $page->replace_template_paramname (
			( ( $this->use_regexes )
				? $task['name']
				: preg_quote ( $task['name'] ) ),
			$task['old_paramname'],
			$task['new_paramname'],
			( isset ( $task['namespace'] ) ? $task['namespace'] : NULL ),
			( isset ( $task['wiki'     ] ) ? $task['wiki'     ] : NULL )
		);
	}


}
