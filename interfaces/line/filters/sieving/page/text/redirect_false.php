<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Non-redirect pages (by page text) filter class
#
#  Does not use the checker object extension!
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_redirect.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/notequals_true.php' );


class Filter_Page_Text_Redirect_False extends
	Filter_Page_Text_Redirect
{

	# ----- Instantiating ----- #

	protected function checker ( $checker_params = NULL )
	{
		return new Checker_NotEquals_True ( $checker_params );
	}


}
