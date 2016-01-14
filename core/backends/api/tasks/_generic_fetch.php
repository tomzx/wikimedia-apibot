<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Generic Fetch.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../../data/page.php' );



abstract class API_Task_GenericFetch extends API_Task
{

	# ----- Constructor ----- #

	function __construct ( $backend, $params = array(), $logpreface = "" )
	{
		parent::__construct ( $backend, $params, $logpreface );

		if ( ! isset ( $this->settings['fetch_objects'] ) )
			$this->settings['fetch_objects'] = true;
	}


}
