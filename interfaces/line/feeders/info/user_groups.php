<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - User groups Info-based feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_user.php' );


class Feeder_Info_User_Groups extends Feeder_Info_User
{

	# ----- Implemented ----- #

	protected function data_type ()
	{
		return "string/usergroupname";
	}

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Groups";
	}


	protected function info_elements_array()
	{
		return $this->core->info->user_groups();
	}


}
