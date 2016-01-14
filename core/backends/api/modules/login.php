<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Login.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class API_Module_Login extends API_Module
{

	protected $mustbeposted = true;


	# ----- Implemented ----- #

	public function modulename ()
	{
		return "login";
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 10700 ) )
			return NULL;  // the module itself appears in 1.7, I think

		$paramdesc = array (
			'prefix' => "lg",
			'mustbeposted' => true,
			'params' => array (
				'name' => array (
					'type' =>"string",
					'required' => true,
				),
				'password' => array (
					'type' => "string",
					'required' => true,
				),
				'domain' => array (
					'type' => "string",
				),
				'token' => array (  // appears in 1.15.3, but appears safe to hardcode
					'type' => "string",
				),
			),
		);

		return $paramdesc;
	}


}
