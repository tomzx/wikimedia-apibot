<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Namespaces Allnames Info-based feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/namespaces.php' );


class Feeder_Info_Namespaces_Allnames extends Feeder_Info_Namespaces
{

	# ----- Implemented ----- #

	protected function data_type ()
	{
		return "string/nsname";
	}

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Allnames";
	}


	protected function info_elements_array()
	{
		return $this->core->info->namespaces_allnames();
	}


}
