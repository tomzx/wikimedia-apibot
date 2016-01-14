<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Exclude Files with speciied property in list filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/nonmatch_items_all.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/_standard_check_callbacks.php' );


class Filter_File_SpecifiedProperty_Exclude extends
	Filter_File_SpecifiedProperty
{

	# ----- Instantiating ----- #

	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".Exclude";
	}


	protected function checker ( $checker_params = NULL )
	{
		return new Checker_NonMatchItems_All ( $checker_params,
			"check_callback__equals" );
	}


}
