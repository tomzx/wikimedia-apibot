<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Symlink Blocksize filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_symlink_diap.php' );


class Filter_DirEntry_Symlink_Blksize extends
	Filter_DirEntry_Symlink_GenericDiap
{

	# ----- Instantiating ----- #

	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".Blksize";
	}


	# ----- Overriding ----- #

	protected function element_to_check ( &$signal )
	{
		$data = parent::element_to_check ( $signal );
		if ( isset ( $data['blksize'] ) )
			return $data['blksize'];
		else
			return NULL;
	}


}
