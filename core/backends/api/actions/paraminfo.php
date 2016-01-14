<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Actions: Paraminfo.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/paraminfo.php' );


class API_Action_Paraminfo extends API_Action
{

	# ----- Implemented ----- #


	protected function module ( $settings )
	{
		return new API_Module_Paraminfo ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


}
