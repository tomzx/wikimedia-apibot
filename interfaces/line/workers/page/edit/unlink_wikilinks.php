<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Unlink wikilinks Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Worker_EditPage_UnlinkWikilinks extends Worker_EditPage
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Unlink.Wikilinks";
	}


	protected function make_edits ( &$data_block )
	{
		$count = 0;

		if ( isset ( $this->tasks['name'] ) )

			$count = $data_block['*']->unlink_wikilink (
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
				( isset ( $this->tasks['text'] )
					? $this->tasks['text']
					: NULL ),
				( isset ( $this->tasks['limit'] )
					? $this->tasks['limit']
					: -1 ) );

		else

			foreach ( $this->tasks as $wikilink )

				$count += $data_block['*']->unlink_wikilink (
					( isset ( $wikilink['colon'] )
						? $wikilink['colon']
						: NULL ),
					( isset ( $wikilink['wiki'] )
						? $wikilink['wiki']
						: NULL ),
					( isset ( $wikilink['namespace'] )
						? $wikilink['namespace']
						: NULL ),
					$wikilink['name'],
					( isset ( $wikilink['anchor'] )
						? $wikilink['anchor']
						: NULL ),
					( isset ( $wikilink['text'] )
						? $wikilink['text']
						: NULL ),
					( isset ( $wikilink['limit'] )
						? $wikilink['limit']
						: -1 ) );

		$this->add_changes ( '$1 wikilink(s) unlinked', $count );

		return true;
	}


}
