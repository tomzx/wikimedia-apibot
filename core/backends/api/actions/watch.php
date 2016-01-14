<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Action: Watch.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/watch.php' );


class API_Action_Watch extends API_Action
{

	public $unwatch;


	# ----- Overriding ----- #


	public function nohooks__set_params ( $hook_object, $params )
	{
		$this->set_token ( $params, $this->backend->tokens->watch_token() );

		return parent::nohooks__set_params ( $hook_object, $params );
	}


	# ----- Implemented ----- #


	protected function module ( $settings )
	{
		return new API_Module_Watch ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


}
