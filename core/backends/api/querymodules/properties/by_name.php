<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Properties: Page property entirely derived from $info.
#  (that is, a property described in the paraminfo but unknown to this code)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Property_ByName extends API_Params_Property
{

	protected $modulename;


	# ----- Constructor (overridden) ----- #

	function __construct ( $hooks, $info, $settings = array(),
		$is_generator = false, $modulename = NULL )
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
		return NULL;
	}


}
