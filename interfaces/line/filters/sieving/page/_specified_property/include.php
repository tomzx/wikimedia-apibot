<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Include Pages with specified property in list filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/match_items_any.php' );


class Filter_Page_SpecifiedProperty_Include extends
	Filter_Page_SpecifiedProperty
{

	# ----- Instantiating ----- #

	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".Include";
	}


	protected function checker ( $checker_params = NULL )
	{
		return new Checker_MatchItems_Any ( $checker_params,
			"check_callback__equals" );
	}


}
