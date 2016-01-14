<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Append text Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Worker_EditPage_AppendText extends Worker_EditPage
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Append.Text";
	}


	protected function make_edits ( &$data_block )
	{
		if ( is_array ( $this->tasks ) )
		{
			foreach ( $this->tasks as $task )
				$data_block['*']->append ( $task );

			$this->add_changes ( '$1 texts appended', count ( $this->tasks ) );
		}
		else
		{
			$data_block['*']->append ( $this->tasks );
			$this->add_changes ( '$1 text appended', 1 );
		}

		return true;
	}


}
