<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generals with Anonymous property On filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/equals_true.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/_standard_check_callbacks.php' );


class Filter_General_Anonymous_True extends Filter_General_Anonymous
{

	# ----- Instantiating ----- #

	protected function checker ( $checker_params = NULL )
	{
		return new Checker_Equals_True ( $checker_params,
			"check_callback__equals" );
	}


}
