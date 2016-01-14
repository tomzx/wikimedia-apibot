<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Page (via revid) fetcher class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_page.php' );
require_once ( dirname ( __FILE__ ) . '/../../../../core/tasks/fetch_revid.php' );


class Fetcher_Wiki_PageRevid extends Fetcher_Wiki_Page
{

	# ----- Overriding ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Revid";
	}


	# ----- Instantiated ----- #

	protected function process_data ( &$signal )
	{
		$this->revid = $signal->data_revid ( $this->default_data_key );
		if ( is_null ( $this->revid ) )
			return false;

		return parent::process_data ( $signal );
	}


}
