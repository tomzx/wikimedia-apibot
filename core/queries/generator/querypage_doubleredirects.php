<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: Generator: Querypage: Doubleredirects.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/querypage.php' );


class Query_Generator_Querypage_Doubleredirects extends Query_Generator
{

	# ----- Overriding ----- #


	protected function api_query ()
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../backends/api/queries/generator/querypage_doubleredirects.php' );
		return new API_Query_Generator_Querypage_Doubleredirects (
			$this->core->backend ( 'API' ) );
	}


	# ----- Implemented ----- #

	protected function query_name ()
	{
		return "querypage_doubleredirects";
	}


	protected function supported_backends ()
	{
		return array ( "api" );
	}


}
