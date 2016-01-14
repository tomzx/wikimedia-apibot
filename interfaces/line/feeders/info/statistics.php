<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Statistics Info-based feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Feeder_Info_Statistics extends Feeder_Info
{

	# ----- Implemented ----- #

	protected function data_type ()
	{
		return "string/statistic";
	}

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Statistics";
	}


	protected function info_elements_array()
	{
		return $this->core->info->statistics();
	}


}
