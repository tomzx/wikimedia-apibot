<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backend-independent Query: List: Recentchanges.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Query_List_Recentchanges extends Query_List
{


	# ----- Overriding ----- #


	protected function api_query ()
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../backends/api/queries/list/recentchanges.php' );
		return new API_Query_List_Recentchanges (
			$this->core->backend ( 'API' ) );
	}


	protected function postprocess_result ( $result )
	{
		if ( $this->settings['return_objects'] )
			$result = new Recentchange ( $this->core, $result );

		return parent::postprocess_result ( $result );
	}


	# ----- Implemented ----- #

	protected function query_name ()
	{
		return "recentchanges";
	}


	protected function supported_backends ()
	{
		return array ( "api" );
	}


}
