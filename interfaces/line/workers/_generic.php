<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic Worker class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_line_slot.php' );


abstract class Worker extends Line_Slot
{

	protected $changes;


	# ----- Instantiating ----- #

	protected function signal_log_slot_type ()
	{
		return "worker";
	}


	# ----- Changes support ----- #

	protected function add_changes ( $desc, $count )
	{
		if ( isset ( $this->changes[$desc] ) )
			$this->changes[$desc] += $count;
		else
			$this->changes[$desc] = $count;
	}


	# ----- Implemented ----- #

	protected function process_data ( &$signal )
	{
		$this->changes = array();
		return parent::process_data ( $signal );
	}


	# ----- Overriding ----- #

	protected function set_jobdata ( $result = NULL,
		$extra_params = array(), $exclude_params = array() )
	{
		return parent::set_jobdata ( $result,
			array_merge ( $this->changes, $extra_params ), $exclude_params );
	}


}
