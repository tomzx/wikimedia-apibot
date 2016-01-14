<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Generic Replacing Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


abstract class Worker_EditPage_Replace extends Worker_EditPage
{

	public $use_regexes = false;


	# ----- Tools ----- #

	protected function replace_and_report ( &$page, $task )
	{
		$count = $this->perform_replace ( $page, $task );
		$report = ( isset ( $task['report'] )
			? $task['report']
			: "$1 replacements" );

		if ( $count )
			$this->add_changes ( $report, $count );
	}


	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Replace";
	}


	protected function make_edits ( &$data_block )
	{
		if ( $this->is_task_single ( $this->tasks ) )
			$this->replace_and_report ( $data_block['*'], $this->tasks );
		else
			foreach ( $this->tasks as $task )
				$this->replace_and_report ( $data_block['*'], $task );

		return true;
	}


	# ----- Abstract ----- #

	abstract protected function is_task_single ( &$task );

	abstract protected function perform_replace ( &$page, $task );


}
