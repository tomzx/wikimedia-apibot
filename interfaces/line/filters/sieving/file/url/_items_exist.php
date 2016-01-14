<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - File last revision Url existence in an array generic filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


abstract class Filter_File_Url_CheckItemsExistence extends
	Filter_File_Url
{

	# ----- Constructor ----- #

	function __construct ( $core, $items )
	{
		if ( ! is_array ( $items ) )
			$items = array ( $items );

		parent::__construct ( $core, $items );
	}


}
