<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Task: Generic Fetching.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../../data/page.php' );



abstract class Web_Task_Fetching extends Web_Task
{

	# ----- Constructor ----- #

	function __construct ( $backend, $params = array(), $logpreface = "" )
	{
		parent::__construct ( $backend, $params, $logpreface );

		if ( ! isset ( $this->settings['fetch_objects'] ) )
			$this->settings['fetch_objects'] = true;
	}


}
