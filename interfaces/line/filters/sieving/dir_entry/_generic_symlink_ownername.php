<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic Symlink Owner name filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_symlink_pwuid.php' );


abstract class Filter_DirEntry_Symlink_OwnerName extends
	Filter_DirEntry_Symlink_PWUID
{

	# ----- Instantiating ----- #

	protected function slotname_preface ()
	{
		return "Symlink_Owner";
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
