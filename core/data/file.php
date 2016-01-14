<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Data objects classes: File
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class File extends Dataobject
{

	# ---- Constructor ----- #

	function __construct ( $core, $title, $body = NULL )
	{
		if ( is_string ( $title ) )  // not a data array
			$title = array ( 'title' => $title, 'body' => $body );

		if ( isset ( $title['imageinfo'] ) && is_array ( $title['imageinfo'] ) )
		{
			$title = array_merge ( $title, $title['imageinfo'] );
			unset ( $title['imageinfo'] );
		}

		parent::__construct ( $core, $title );
	}


}
