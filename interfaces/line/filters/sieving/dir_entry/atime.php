<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - File Last Access time filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_file_timediap.php' );


class Filter_DirEntry_File_ATime extends
	Filter_DirEntry_File_Time
{

	# ----- Overriding ----- #

	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".ATime";
	}


	# ----- Overriding ----- #

	protected function element_to_check ( &$signal )
	{
		$data = parent::element_to_check ( $signal );
		if ( isset ( $data['atime'] ) )
			return $data['atime'];
		else
			return NULL;
	}


}
