<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Categories don't exist (all) in pages text filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_categories_exist.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/nonmatch_items_all.php' );


class Filter_Page_Text_CategoriesNotExist_All extends
	Filter_Page_Text_CheckCategoriesExistence
{

	# ----- Instantiating ----- #

	protected function checker ( $checker_params = NULL )
	{
		return new Checker_NonMatchItems_All ( $checker_params,
			array ( $this, "category_exists" ) );
	}


}
