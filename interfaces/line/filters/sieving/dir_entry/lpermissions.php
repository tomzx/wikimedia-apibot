<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Symlink permissions filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_symlink_stat.php' );
require_once ( dirname ( __FILE__ ) . '/../_checks/file_permissions.php' );



class Filter_DirEntry_Symlink_Permissions extends
	Filter_DirEntry_Symlink_WithStat
{

	# ----- Instantiating ----- #

	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".Permissions";
	}


	protected function checker ( $checker_params = NULL )
	{
		return new Checker_FilePermissions ( $checker_params );
	}


	# ----- Overriding ----- #

	protected function element_to_check ( &$signal )
	{
		$data = parent::element_to_check ( $signal );
		if ( isset ( $data['mode'] ) )
			return $data['mode'];
		else
			return NULL;
	}


}
