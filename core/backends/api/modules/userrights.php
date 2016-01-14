<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Userrights.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Module_Userrights extends API_Module
{

	protected $mustbeposted = true;


	# ----- Implemented ----- #

	public function modulename ()
	{
		return "userrights";
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11600 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "",
			'readrights' => true,
			'writerights' => true,
			'mustbeposted' => true,
			'params' => array (
				'user' => array (
					'type' => "string",
					'required' => true,
				),
				'token' => array (
					'type' => "string",
				),
				'add' => array (
					'type' => array (
						"bot",
						"bureaucrat",
						"sysop",
					),
					'multi' => true,
					'limit' => 50,
				),
				'remove' => array (
					'type' => array (
						"bot",
						"bureaucrat",
						"sysop",
					),
					'multi' => true,
					'limit' => 50,
				),
				'reason' => array (
					'type' => "string",
				),
			),
		);

		return $paramdesc;
	}


}
