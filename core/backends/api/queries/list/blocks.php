<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: List: Blocks.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Query_List_Blocks extends API_Query_List
{

	# ----- Implemented ----- #

	public function queryname ()
	{
		return "blocks";
	}


}
