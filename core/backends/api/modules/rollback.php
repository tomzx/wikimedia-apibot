<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Rollback.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Module_Rollback extends API_Module
{

	protected $mustbeposted = true;


	# ----- Implemented ----- #

	public function modulename ()
	{
		return "rollback";
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11200 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "",
			'readrights' => true,
			'writerights' => true,
			'mustbeposted' => true,
			'params' => array (
				'title' => array (
					'type' => "string",
					'required' => true,
				),
				'user' => array (
					'type' => "string",
					'required' => true,
				),
				'token' => array (
					'type' => "string",
				),
				'summary' => array (
					'type' => "string",
				),
				'markbot' => array (
					'type' => "boolean",
					'default' => false,
				),
			),
		);

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['watchlist'] = array (
				'type' => array (
					"watch",
					"unwatch",
					"preferences",
					"nochange",
				),
				'default' => "preferences",
			);
		}

		return $paramdesc;
	}


}
