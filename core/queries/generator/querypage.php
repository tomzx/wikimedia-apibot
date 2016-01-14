<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backend-independent Query: Generator: Querypage.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Query_Generator_Querypage extends Query_Generator
{

	protected $pagename;


	# ----- Constructor ----- #


	function __construct ( $pagename, $core )
	{
		$this->pagename = $pagename;
		parent::__construct ( $core );
	}


	# ----- Overriding ----- #


	protected function api_query ()
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../backends/api/queries/generator/querypage.php' );
		return new API_Query_Generator_Querypage ( $this->pagename,
			$this->core->backend ( 'API' ) );
	}


	# ----- Implemented ----- #

	protected function query_name ()
	{
		return "querypage_" . $this->pagename;
	}


	protected function supported_backends ()
	{
		return array ( "api" );
	}


}
