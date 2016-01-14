<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Action: Protect.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/protect.php' );


class API_Action_Protect extends API_Action
{

	public $protections; // array ( 'type' => "level" ) or bar-separated string
	public $expiry; // array or bar-separated list of timestamps matching protections

	public $cascade;

	public $reason;


	# ----- Overriding ----- #


	public function nohooks__set_params ( $hook_object, $params )
	{
		$this->set_token ( $params, $this->backend->tokens->protect_token() );

		return parent::nohooks__set_params ( $hook_object, $params );
	}


	# ----- Implemented ----- #

	protected function module ( $settings )
	{
		return new API_Module_Protect ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


}
