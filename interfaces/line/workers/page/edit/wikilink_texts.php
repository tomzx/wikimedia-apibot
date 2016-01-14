<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Wikilink texts Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Worker_EditPage_WikilinkTexts extends Worker_EditPage
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Wikilink.Texts";
	}


	protected function make_edits ( &$data_block )
	{
		$count = 0;

		if ( isset ( $this->tasks['text'] ) )

			$count = $data_block['*']->wikilink_text (
				$this->tasks['text'],
				( isset ( $this->tasks['colon'] )
					? $this->tasks['colon']
					: NULL ),
				( isset ( $this->tasks['wiki'] )
					? $this->tasks['wiki']
					: NULL ),
				( isset ( $this->tasks['namespace'] )
					? $this->tasks['namespace']
					: NULL ),
				$this->tasks['name'],
				( isset ( $this->tasks['anchor'] )
					? $this->tasks['anchor']
					: NULL ),
				( isset ( $this->tasks['limit'] )
					? $this->tasks['limit']
					: 1 ) );

		else

			foreach ( $this->tasks as $task )

				$count += $data_block['*']->wikilink_text (
					$task['text'],
					( isset ( $task['colon'] )
						? $task['colon']
						: NULL ),
					( isset ( $task['wiki'] )
						? $task['wiki']
						: NULL ),
					( isset ( $task['namespace'] )
						? $task['namespace']
						: NULL ),
					$task['name'],
					( isset ( $task['anchor'] )
						? $task['anchor']
						: NULL ),
					( isset ( $task['limit'] )
						? $task['limit']
						: 1 ) );

		$this->add_changes ( '$1 text(s) wikilinked', $count );

		return true;
	}


}
