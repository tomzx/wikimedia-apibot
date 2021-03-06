<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backend-independent Query: Meta: Siteinfo_Namespacealiases.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Query_Meta_Siteinfo_Namespacealiases extends Query_Meta
{


	# ----- Overriding ----- #


	protected function api_query ()
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../backends/api/queries/meta/siteinfo_namespacealiases.php' );
		return new API_Query_Meta_Siteinfo_Namespacealiases (
			$this->core->backend ( 'API' ) );
	}


	# ----- Implemented ----- #

	protected function query_name ()
	{
		return "siteinfo_namespacealiases";
	}


	protected function supported_backends ()
	{
		return array ( "api" );
	}


}
