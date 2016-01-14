<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Property entirely derived from $info.
#  (that is, a list described in the paraminfo but unknown to this code)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_ByName extends API_Params_Query_List
{

	protected $modulename;


	# ----- Constructor (overridden) ----- #

	function __construct ( $hooks, $info, $settings, $is_generator, $modulename )
	{
		$this->modulename = $modulename;
		parent::__construct ( $hooks, $info, $settings, $is_generator );
	}


	# ----- Implemented ----- #

	public function modulename ()
	{
		return $this->modulename;
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{  // not expected to be called
		throw new ApibotException_InternalError (
			"Cannot create paramobject for module " . $this->modulename .
			" without site info" );
	}


}
