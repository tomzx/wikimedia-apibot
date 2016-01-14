<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backend-independent Query: Generator: Random.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Query_Generator_Random extends Query_Generator
{


	# ----- Overriding ----- #


	protected function api_query ()
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../backends/api/queries/generator/random.php' );
		return new API_Query_Generator_Random (
			$this->core->backend ( 'API' ) );
	}


	# ----- Implemented ----- #

	protected function query_name ()
	{
		return "random";
	}


	protected function supported_backends ()
	{
		return array ( "api" );
	}


}
