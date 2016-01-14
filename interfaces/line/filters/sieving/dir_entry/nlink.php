<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - File NLink filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_file_diap.php' );


class Filter_DirEntry_File_NLink extends
	Filter_DirEntry_File_Diap
{

	# ----- Instantiating ----- #

	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".NLink";
	}


	# ----- Overriding ----- #

	protected function element_to_check ( &$signal )
	{
		$data = parent::element_to_check ( $signal );
		if ( isset ( $data['nlink'] ) )
			return $data['nlink'];
		else
			return NULL;
	}


}
