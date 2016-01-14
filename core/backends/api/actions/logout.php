<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Action: Logout.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/logout.php' );


class API_Action_Logout extends API_Action
{

	# ----- Implemented ----- #


	protected function module ( $settings )
	{
		return new API_Module_Logout ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


}
