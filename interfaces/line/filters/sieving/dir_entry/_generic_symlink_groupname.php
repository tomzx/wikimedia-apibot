<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic Symlink Group name filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_symlink_grgid.php' );


abstract class Filter_DirEntry_Symlink_GroupName extends
	Filter_DirEntry_Symlink_GRGID
{

	# ----- Instantiating ----- #

	protected function slotname_preface ()
	{
		return "Symlink_Group";
	}


	# ----- Overriding ----- #

	protected function element_to_check ( &$signal )
	{
		$data = parent::element_to_check ( $signal );
		if ( isset ( $data['name'] ) )
			return $data['name'];
		else
			return NULL;
	}


}
