<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backend-independent Query: Meta: Siteinfo_Dbrepllag.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Query_Meta_Siteinfo_Dbrepllag extends Query_Meta
{


	# ----- Overriding ----- #


	protected function api_query ()
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../backends/api/queries/meta/siteinfo_dbrepllag.php' );
		return new API_Query_Meta_Siteinfo_Dbrepllag (
			$this->core->backend ( 'API' ) );
	}


	# ----- Implemented ----- #

	protected function query_name ()
	{
		return "siteinfo_dbrepllag";
	}


	protected function supported_backends ()
	{
		return array ( "api" );
	}


}
