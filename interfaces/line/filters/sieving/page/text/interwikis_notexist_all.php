<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Interwikis don't exist (all) in pages text filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_interwikis_exist.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/nonmatch_items_all.php' );


class Filter_Page_Text_InterwikisNotExist_All extends
	Filter_Page_Text_CheckInterwikisExistence
{

	# ----- Instantiating ----- #

	protected function checker ( $checker_params = NULL )
	{
		return new Checker_NonMatchItems_All ( $checker_params,
			array ( $this, "interwiki_exists" ) );
	}


}
