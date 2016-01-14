<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Pages (by titles) batch fetcher class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_batch.php' );
require_once ( dirname ( __FILE__ ) . '/../../../../core/data/page.php' );


class Fetcher_Wiki_Batch_Titles extends Fetcher_Wiki_Batch
{


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Titles";
	}


	# ----- Implemented ----- #


	protected function element_to_signal ( $element )
	{
		$page = new Page ( $this->core, $element );
		return new LineSignal_Data ( $page, "object/page", NULL );
	}


}
