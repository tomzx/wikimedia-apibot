<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - File Owner name in a list filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_file_ownername.php' );
require_once ( dirname ( __FILE__ ) . '/../_checks/match_items_any.php' );
require_once ( dirname ( __FILE__ ) . '/../_checks/_standard_check_callbacks.php' );


class Filter_DirEntry_File_Owner extends
	Filter_DirEntry_File_OwnerName
{

	# ----- Instantiated ----- #

	protected function checker ( $checker_params = NULL )
	{
		if ( ! is_array ( $checker_params ) )
			$checker_params = array ( $checker_params );

		return new Checker_MatchItems_Any ( $checker_params,
			"check_callback__match_regex_withneg" );
	}


}
