<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Strings exist (any) in pages text filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_strings_exist.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/match_items_any.php' );


class Filter_Page_Text_StringsExist_Any extends
	Filter_Page_Text_CheckStringsExistence
{

	# ----- Instantiating ----- #

	protected function checker ( $checker_params = NULL )
	{
		return new Checker_MatchItems_Any ( $checker_params,
			array ( $this, "string_exists" ) );
	}


}
