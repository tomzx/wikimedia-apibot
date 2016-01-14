<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_line_slot.php' );


abstract class Writer extends Line_Slot
{


	# ----- Instantiating ----- #

	protected function signal_log_slot_type ()
	{
		return "writer";
	}


	# ----- Overriding ----- #

	protected function end_of_line_result ( &$signal )
	{
		return true;  // default for writers
	}


	# ----- Tools ----- #

	protected function changes_string ( $changes )
	{
		$changestrings = array();
		if ( is_array ( $changes ) )
			foreach ( $changes as $desc => $count )
				if ( is_array ( $count ) )
					$changestrings[] = $desc . "(" . $this->changes_string ( $count ) . ")";
				else
					$changestrings[] = str_replace ( '$1', $count, $desc );

		return implode ( ',', $changestrings );
	}


	protected function changes_summary ( &$signal )
	{
		$strings = array();
		foreach ( $signal->log as $logentry )
			if ( ( $logentry['type'] == "worker" ) &&
				isset ( $logentry['job']['changes'] ) )

				$strings[] = $this->changes_string ( $logentry['job']['changes'] );

		return implode ( '; ', $strings );
	}


}
