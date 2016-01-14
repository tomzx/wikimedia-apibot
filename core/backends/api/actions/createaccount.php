<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Action: Createaccount.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/createaccount.php' );


class API_Action_Createaccount extends API_Action
{

	public $name;
	public $password;  // ignored if $mailpassword is set
	public $domain;  // for external authentication
	public $email;  // mandatory if $mailpassword is set
	public $realname;  // might not be enabled by the wiki; check?
	public $mailpassword;  // if set, random pwd will be sent to email
	public $reason;  // explanation for the MW logs
	public $language;  // MediaWiki language code


	# ----- Implemented ----- #


	protected function module ( $settings )
	{
		return new API_Module_Createaccount ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


	# ----- Overriding ----- #


	public function nohooks__xfer ( $hook_object )
	{
		$orig_params = $this->get_params();

		$result = parent::nohooks__xfer ( $hook_object, );

		if ( isset ( $this->data['error'] ) &&
			( $this->data['error']['code'] == "nocookiesfornew" ) )
		{
			// could be a bug in early versions of MW 1.21 - repeating once gets around
			$this->set_params ( $orig_params );
			$result = parent::nohooks__xfer ( $hook_object );
		}

		if ( ! isset ( $this->data['error'] ) &&
			isset ( $this->data['createaccount']['result'] ) &&
			( $this->data['createaccount']['result'] == "needtoken" ) )
		{
			$orig_params['token'] = $this->data['createaccount']['token'] );
			$this->set_params ( $orig_params );
			$result = parent::nohooks__xfer ( $hook_object );
		}

		return $result;
	}


}
