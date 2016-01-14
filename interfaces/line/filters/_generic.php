<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic Filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_line_slot.php' );



abstract class Filter extends Line_Slot
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_type ()
	{
		return "filter";
	}


	# ----- Overriding ----- #

	protected function set_jobdata ( $result = NULL,
		$extra_params = array(), $exclude_params = array() )
	{
		$extra_params = array_merge ( $this->job_params(), $extra_params );

		parent::set_jobdata ( $result, $extra_params, $exclude_params );
	}


	# ----- Abstract ----- #

	abstract protected function job_params ();


}
