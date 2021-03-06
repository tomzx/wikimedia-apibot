<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: List: Querypage: Uncategorizedpages.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/querypage.php' );


class Query_List_Querypage_Uncategorizedpages extends Query_List
{

	# ----- Overriding ----- #


	protected function api_query ()
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../backends/api/queries/list/querypage_uncategorizedpages.php' );
		return new API_Query_List_Querypage_Uncategorizedpages (
			$this->core->backend ( 'API' ) );
	}


	# ----- Implemented ----- #

	protected function query_name ()
	{
		return "querypage_uncategorizedpages";
	}


	protected function supported_backends ()
	{
		return array ( "api" );
	}


}
