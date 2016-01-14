<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Action: Purge.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/purge.php' );


class API_Action_Purge extends API_Action
{

	# ----- Implemented ----- #


	protected function module ( $settings )
	{
		return new API_Module_Purge ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


}
