<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Action: ByName.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/by_name.php' );


class API_Action_ByName extends API_Action
{

	protected $modulename;


	# ----- Constructor ----- #

	function __construct ( $backend, $params = NULL, $modulename = NULL )
	{
		$this->modulename = $modulename;
		parent::__construct ( $backend, $params );
	}


	# ----- Implemented ----- #

	protected function module ( $setparams )
	{
		return new API_Module_ByName ( $this->backend->exchanger,
			$this->backend->info, $setparams, $this->modulename );
	}


}
