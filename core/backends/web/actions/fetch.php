<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Action: Fetch.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/fetch.php' );



class Web_Action_Fetch extends Web_Action
{

	public $section;


	# ----- Implemented ----- #


	protected function module ( $settings )
	{
		return new Web_Module_Fetch ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


}
