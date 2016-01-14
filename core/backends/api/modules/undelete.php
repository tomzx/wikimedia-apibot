<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Undelete.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Module_Undelete extends API_Module
{

	protected $mustbeposted = true;


	# ----- Implemented ----- #

	public function modulename ()
	{
		return "undelete";
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
				),
				'token' => array (
					'type' => "string",
				),
				'reason' => array (
					'type' => "string",
				),
				'timestamps' => array (
					'type' => "timestamp",
					'multi' => true,
					'limit' => 50,
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
