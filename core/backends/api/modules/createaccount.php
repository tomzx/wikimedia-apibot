<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Createaccount.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class API_Module_Createaccount extends API_Module
{

	protected $mustbeposted = true;


	# ----- Implemented ----- #

	public function modulename ()
	{
		return "createaccount";
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 12100 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "",
			'readrights' => true,
			'writerights' => true,
			'mustbeposted' => true,
			'params' => array (
				'name' => array (
					'type' => "string",
					'required' => true,
				),
				'password' => array (  // will not be used if mailpassword is set
					'type' => "string",
				),
				'domain' => array (  // for external authentication
					'type' => "string",
				),
				'token' => array (  // one-time only - should not be cached!
					'type' => "string",
					'default' => "",
				),
				'email' => array (  // should be set if mailpassword is set
					'type' => "string",
				),
				'realname' => array (  // maybe check wiki if it is supported?
					'type' => "string",
				),
				'mailpassword' => array (
					'type' => "boolean",
				),
				'reason' => array (  // something to show up in the MW logs
					'type' => "string",
				),
				'language' => array (  // MW language code for this account
					'type' => "string",
				),
				// captcha extensions may add also captchaid and captchaword -
				// implement these in a captcha extension after hooks are implemented!
			),
		);

		return $paramdesc;
	}


}
