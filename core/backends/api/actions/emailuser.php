<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Action: Emailuser.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/emailuser.php' );


class API_Action_Emailuser extends API_Action
{

	public $user;
	public $text;
	public $subject;
	public $ccme;


	# ----- Overriding ----- #


	public function nohooks__set_params ( $hook_object, $params )
	{
		$this->set_token ( $params, $this->backend->tokens->emailuser_token() );

		return parent::nohooks__set_params ( $hook_object, $params );
	}


	# ----- Implemented ----- #

	protected function module ( $settings )
	{
		return new API_Module_Emailuser ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


}
