<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Regexes (any) match page text filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_regexes_exist.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/match_items_any.php' );


class Filter_Page_Text_RegexesExist_Any extends
	Filter_Page_Text_CheckRegexesExistence
{

	# ----- Instantiating ----- #

	protected function checker ( $checker_params = NULL )
	{
		return new Checker_MatchItems_Any ( $checker_params,
			array ( $this, "regex_exists" ) );
	}


}
