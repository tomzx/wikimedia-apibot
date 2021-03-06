<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - File Group name NOT in a list filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_file_groupname.php' );
require_once ( dirname ( __FILE__ ) . '/../_checks/nonmatch_items_all.php' );


class Filter_DirEntry_File_NotGroup extends
	Filter_DirEntry_File_Groupname
{

	# ----- Instantiating ----- #

	protected function checker ( $checker_params = NULL )
	{
		if ( ! is_array ( $checker_params ) )
			$checker_params = array ( $checker_params );

		return new Checker_NonMatchItems_All ( $checker_params,
			array ( $this, "check" ) );
	}


	# ----- New ----- #

	public function check ( $group, $regex )
	{
		return (bool) preg_match ( $regex, $group );
	}


}
