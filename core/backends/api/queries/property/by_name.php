<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: Property: Module by paraminfo.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Query_Property_ByName extends API_Query_Property
{

	protected $modulename;

	// no default params can be specified

	# ----- Constructor ----- #

	function __construct ( $modulename, $backend, $settings = array(),
		$defaults = array() )
	{
		$this->modulename = $modulename;
		parent::__construct ( $backend, $settings, $defaults );
	}


	# ----- Overriding ----- #

	protected function action ( $settings = array(), $defaults = array() )
	{
		if ( $this->backend->info->is_available_property ( $this->modulename ) )
			$this->action = parent::action ( $settings, $defaults );
		else
			throw new ApibotException_InternalError ( "API module " .
				$this->modulename . " is unknown at this wiki" );
	}


	# ----- Implemented ----- #

	public function queryname ()
	{
		return $this->modulename;
	}


}
