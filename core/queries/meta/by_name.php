<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backend-independent Query: Meta: Module by paraminfo.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Query_Meta_ByName extends Query_Meta
{

	protected $modulename;


	# ----- Constructor ----- #

	function __construct ( $modulename, $core )
	{
		if ( ! $this->core->info->is_available_meta ( $modulename ) )
			throw new ApibotException_InternalError ( "Query module " .
				$modulename . " is unknown at this wiki" );

		$this->modulename = $modulename;
		parent::__construct ( $core );
	}


	# ----- Overriding ----- #


	protected function api_query ()
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../backends/api/queries/meta/by_name.php' );
		return new API_Query_Meta_ByName (
			$this->core->backend ( 'API' ) );
	}


	# ----- Implemented ----- #

	protected function query_name ()
	{
		return "by_name";
	}


	protected function supported_backends ()
	{
		return array ( "api" );
	}


}
