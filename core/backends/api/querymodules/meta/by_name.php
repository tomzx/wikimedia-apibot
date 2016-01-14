<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Meta: Meta entirely derived from $info.
#  (that is, a meta described in the paraminfo but unknown to this code)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Meta_ByName extends API_Params_Query_Meta
{

	protected $modulename;


	# ----- Constructor (overridden) ----- #

	function __construct ( $hooks, $info, $settings = array(), $modulename = NULL )
	{
		$this->modulename = $modulename;
		parent::__construct ( $hooks, $info, $settings );
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
