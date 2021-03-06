<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Non-new Files (by File info property) filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/equals_params.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/_standard_check_callbacks.php' );


class Filter_File_Imagerepository_Local extends Filter_File_Imagerepository
{

	# ----- Instantiating ----- #

	protected function checker ( $checker_params = NULL )
	{
		return new Checker_Equals_Params ( "local",
			"check_callback__equals" );
	}


}
