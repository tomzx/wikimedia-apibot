<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Wikilinks exist (all) in pages text filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_wikilinks_exist.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/match_items_all.php' );


class Filter_Page_Text_WikilinksExist_All extends
	Filter_Page_Text_CheckWikilinksExistence
{

	# ----- Instantiating ----- #

	protected function checker ( $checker_params = NULL )
	{
		return new Checker_MatchItems_All ( $checker_params,
			array ( $this, "wikilink_exists" ) );
	}


}
