<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Showhooks Info-based feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Feeder_Info_Showhooks extends Feeder_Info
{

	# ----- Implemented ----- #

	protected function data_type ()
	{
		return "array/hook";
	}

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Showhooks";
	}


	protected function info_elements_array()
	{
		return $this->core->info->showhooks();
	}


}
