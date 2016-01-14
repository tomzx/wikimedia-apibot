<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic Symlink dir entry filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


abstract class Filter_DirEntry_Symlink extends Filter_DirEntry
{

	# ----- Overriding ----- #

	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".Symlink";
	}


}
