<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Wikifiles storage generic feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) .
	'/../../../../interfaces/line/feeders/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../libs/_generic.php' );



abstract class Feeder_WikifilesStorage extends Feeder
{


	protected $wfstorage;


	# ----- Constructor ----- #

	function __construct ( $core, $wfstorage, $start_params = array() )
	{
		$this->wfstorage = $wfstorage;
		parent::__construct ( $core, $start_params );
	}


	# ----- Implemented ----- #


	protected function signal_log_slot_name ()
	{
		return "Wikifiles_Storage";
	}


	protected function signal_log_job ()
	{
		return array (
			'params' => $this->get_params(),
		);
	}


}
