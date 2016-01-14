<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - File type in a list filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_file_type.php' );
require_once ( dirname ( __FILE__ ) . '/../_checks/match_items_any.php' );


class Filter_DirEntry_File_Type extends
	Filter_DirEntry_File_TypeCheck
{

	# ----- Instantiated ----- #

	protected function checker ( $checker_params = NULL )
	{
		return new Checker_MatchItems_Any ( $this->types ( $checker_params ),
			NULL );
	}


}
