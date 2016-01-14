<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Emailuser.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Module_Emailuser extends API_Module
{

	protected $mustbeposted = true;


	# ----- Implemented ----- #

	public function modulename ()
	{
		return "emailuser";
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11300 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "",
			'readrights' => true,
			'writerights' => true,
			'mustbeposted' => true,
			'params' => array (
				'target' => array (
					'type' => "string",
					'required' => true,
				),
				'subject' => array (
					'type' => "string",
				),
				'text' => array (
					'type' => "string",
					'required' => true,
				),
				'token' => array (
					'type' => "string",
				),
				'ccme' => array (
					'type' => "boolean",
					'default' => false,
				),
			),
		);

		return $paramdesc;
	}


}
