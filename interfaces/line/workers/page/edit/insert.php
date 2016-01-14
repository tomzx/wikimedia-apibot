<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Insert text Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Worker_EditPage_Insert extends Worker_EditPage
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Insert.Texts";
	}


	protected function make_edits ( &$data_block )
	{
		$count = 0;

		if ( isset ( $this->tasks['text'] ) )

			$count = $data_block['*']->insert ( $this->tasks['text'],
				( isset ( $this->tasks['regexpart_before'] )
					? $this->tasks['regexpart_before']
					: "" ),
				( isset ( $this->tasks['regexpart_after'] )
					? $this->tasks['regexpart_after']
					: "" ),
				( isset ( $this->tasks['limit'] ) ? $this->tasks['limit'] : -1 ) );

		else

			foreach ( $this->tasks as $task )

				$count += $data_block['*']->insert ( $task['text'],
					( isset ( $task['regexpart_before'] )
						? $task['regexpart_before']
						: "" ),
					( isset ( $task['regexpart_after'] )
						? $task['regexpart_after']
						: "" ),
					( isset ( $task['limit'] ) ? $task['limit'] : -1 ) );

		$this->add_changes ( '$1 text(s) inserted', $count );

		return true;
	}


}
