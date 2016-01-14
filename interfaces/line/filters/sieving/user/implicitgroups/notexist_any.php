<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Exclude users with implicit groups in list filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/nonmatch_items_any.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/_standard_check_callbacks.php' );


class Filter_User_ImplicitGroups_NotExist_Any extends
	Filter_User_ImplicitGroups
{

	# ----- Instantiating ----- #

	protected function checker ( $checker_params = NULL )
	{
		return new Checker_NonMatchItems_Any ( $checker_params,
			"check_callback__in_array" );
	}


}
