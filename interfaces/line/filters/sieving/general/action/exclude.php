<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Exclude wiki object Actions in list filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/nonmatch_items_all.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/_standard_check_callbacks.php' );


class Filter_General_Action_Exclude extends
	Filter_General_Action
{

	# ----- Instantiating ----- #

	protected function checker ( $checker_params = NULL )
	{
		return new Checker_NonMatchItems_All ( $checker_params,
			"check_callback__equals" );
	}


	# ----- Overriding ----- #

	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".Exclude";
	}


}
