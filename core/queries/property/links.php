<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backend-independent Query: Property: Links.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../data/page.php' );


class Query_Property_Links extends Query_Property
{


	# ----- Overriding ----- #


	protected function api_query ()
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../backends/api/queries/property/links.php' );
		return new API_Query_Property_Links (
			$this->core->backend ( 'API' ) );
	}


	protected function postprocess_result ( $result )
	{
		if ( $this->settings['return_objects'] )
			$result = new Page ( $this->core, $result );

		return parent::postprocess_result ( $result );
	}


	# ----- Implemented ----- #

	protected function query_name ()
	{
		return "links";
	}


	protected function supported_backends ()
	{
		return array ( "api" );
	}


}
