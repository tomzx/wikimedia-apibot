<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Delete.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Module_Delete extends API_Module
{

	protected $mustbeposted = true;


	# ----- Implemented ----- #

	public function modulename ()
	{
		return "delete";
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
				'pageid' => array (
					'type' => "integer",
				),
				'token' => array (
					'type' => "string",
				),
				'reason' => array (
					'type' => "string",
				),
			),
		);

		if ( $mwverno >= 11300 )
		{
			$paramdesc['params']['watch'] = array (
				'type' => "boolean",
				'default' => false,
			);
			$paramdesc['params']['unwatch'] = array (
				'type' => "boolean",
				'default' => false,
			);
			$paramdesc['params']['oldimage'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['pageid'] = array (
				'type' => "integer",
			);
		}

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

			$paramdesc['params']['watch']['deprecated'] = true;
			$paramdesc['params']['unwatch']['deprecated'] = true;
		}

		return $paramdesc;
	}


}
