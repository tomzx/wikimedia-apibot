<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Filelinks exist (all) in pages text filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_filelinks_exist.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/match_items_all.php' );


class Filter_Page_Text_FilelinksExist_All extends
	Filter_Page_Text_CheckFilelinksExistence
{

	# ----- Instantiating ----- #

	protected function checker ( $checker_params = NULL )
	{
		return new Checker_MatchItems_All ( $checker_params,
			array ( $this, "filelink_exists" ) );
	}


}
