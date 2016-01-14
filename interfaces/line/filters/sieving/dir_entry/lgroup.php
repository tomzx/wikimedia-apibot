<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Symlink Group name in a list filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_symlink_groupname.php' );
require_once ( dirname ( __FILE__ ) . '/../_checks/match_items_any.php' );


class Filter_DirEntry_Symlink_Group extends
	Filter_DirEntry_Symlink_GroupName
{

	# ----- Instantiating ----- #

	protected function checker ( $checker_params = NULL )
	{
		if ( ! is_array ( $checker_params ) )
			$checker_params = array ( $checker_params );

		return new Checker_MatchItems_Any ( $checker_params,
			array ( $this, "check" ) );
	}


	# ----- New ----- #

	public function check ( $group, $regex )
	{
		return (bool) preg_match ( $regex, $group );
	}


}
