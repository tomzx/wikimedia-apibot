<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backend-independent Query: List: ByName.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Query_List_ByName extends Query_List
{

	protected $modulename;


	# ----- Constructor ----- #


	function __construct ( $modulename, $core )
	{
		$this->modulename = $modulename;
		parent::__construct ( $core );
	}


	# ----- Overriding ----- #


	protected function api_query ()
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../backends/api/queries/list/by_name.php' );
		return new API_Query_List_ByName ( $this->modulename,
			$this->core->backend ( 'API' ) );
	}


	# ----- Implemented ----- #

	protected function query_name ()
	{
		return "ByName_" . $this->modulename;
	}


	protected function supported_backends ()
	{
		return array ( "api" );
	}


}
