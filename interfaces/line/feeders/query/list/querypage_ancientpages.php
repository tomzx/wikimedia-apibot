<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Ancientpages Querypage List feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_querypage.php' );



class Feeder_Query_List_Querypage_Ancientpages extends
	Feeder_Query_List_QuerypageGeneric
{

	# ----- Overriding ----- #

	protected function query ( &$core )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../../../../core/queries/list/querypage_ancientpages.php' );
		return new Query_List_Querypage_Ancientpages ( $core );
	}


}