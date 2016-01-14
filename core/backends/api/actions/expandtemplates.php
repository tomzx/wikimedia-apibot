<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Action: Expandtemplates.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/expandtemplates.php' );


class API_Action_Expandtemplates extends API_Action
{

	public $text;
	public $title;


	# ----- Implemented ----- #

	protected function module ( $settings )
	{
		return new API_Module_Expandtemplates ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


}
