<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: ByName.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Module_ByName extends API_Module
{

	protected $modulename;


	# ----- Constructor ----- #

	function __construct ( $exchanger, $info, $setparams, $modulename )
	{
		parent::__construct ( $exchanger, $info, $setparams );
		$this->modulename = $modulename;
	}


	# ----- Implemented ----- #

	public function modulename ()
	{
		return $this->modulename;
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		throw new ApibotException_InternalError (
			"Cannot create paramobject for module " . $this->modulename .
			" without site info" );
	}


}
