<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - File Filename (without extension) filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_file_namepart.php' );


class Filter_DirEntry_File_Filename extends
	Filter_DirEntry_File_Namepart
{

	# ----- Instantiating ----- #

	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".Filename";
	}


	# ----- Overriding ----- #

	protected function element_to_check ( &$signal )
	{
		$data = parent::element_to_check ( $signal );
		if ( isset ( $data['filename'] ) )
			return $data['filename'];
		else
			return NULL;
	}


}
