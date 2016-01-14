<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Task: Generic: Changing.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



abstract class Web_Task_Changing extends Web_Task
{

	# ----- Constructor ----- #

	function __construct ( $backend, $params = array(), $logpreface = "" )
	{
		parent::__construct ( $backend, $params, $logpreface );

		if ( ! isset ( $this->settings['simulate_changes'] ) )
			$this->settings['simulate_changes'] = false;
	}


	# ----- Tools ----- #

	protected function simulation ( $message, $params )
	{
		if ( $this->settings['simulate_changes'] )
		{
			foreach ( $params as $name => $value )
				$message = str_replace ( '$' . $name, $value, $message );

			echo "Simulation: $message\n";
			return true;
		}
		else
		{
			return false;
		}
	}


}
