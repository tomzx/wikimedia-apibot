<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Templates don't exist (any) in pages text filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_templates_exist.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/nonmatch_items_any.php' );


class Filter_Page_Text_TemplatesNotExist_Any extends
	Filter_Page_Text_CheckTemplatesExistence
{

	# ----- Instantiating ----- #

	protected function checker ( $checker_params = NULL )
	{
		return new Checker_NonMatchItems_Any ( $checker_params,
			array ( $this, "template_exists" ) );
	}


}
