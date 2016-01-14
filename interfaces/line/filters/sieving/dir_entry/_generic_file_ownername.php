<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic File Owner name filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_file_pwuid.php' );


abstract class Filter_DirEntry_File_OwnerName extends
	Filter_DirEntry_File_PWUID
{

	# ----- Instantiating ----- #

	protected function slotname_preface ()
	{
		return "File_Owner";
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
