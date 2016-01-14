<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Adding wikilinks Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Worker_EditPage_AddWikilinks extends Worker_EditPage
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Add.Wikilinks";
	}


	protected function make_edits ( &$data_block )
	{
		$count = 0;

		if ( isset ( $this->tasks['wikilink'] ) )

			if ( $data_block['*']->add_wikilink ( $this->tasks['wikilink'],
				( isset ( $this->tasks['regexpart_before'] )
					? $this->tasks['regexpart_before']
					: NULL ),
				( isset ( $this->tasks['regexpart_after'] )
					? $this->tasks['regexpart_after']
					: NULL ),
				( isset ( $this->tasks['limit'] )
					? $this->tasks['limit']
					: NULL ) ) )

				$count = 1;

		else

			foreach ( $this->tasks as $task )

				if ( $data_block['*']->add_wikilink ( $task['wikilink'],
					( isset ( $task['regexpart_before'] )
						? $task['regexpart_before']
						: NULL ),
					( isset ( $task['regexpart_after'] )
						? $task['regexpart_after']
						: NULL ),
					( isset ( $task['limit'] )
						? $task['limit']
						: NULL ) ) )

					$count += 1;

		$this->add_changes ( '$1 wikilink(s) added', $count );

		return true;
	}


}
