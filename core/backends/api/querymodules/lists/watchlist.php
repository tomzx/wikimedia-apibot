<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Watchlist.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Watchlist extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "watchlist";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 10900 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "wl",
			'generator' => true,
			'params' => array (
				'start' => array (
					'type' => "timestamp",
				),
				'end' => array (
					'type' => "timestamp",
				),
				'dir' => array (
					'type' => array (
						"newer",
						"older",
					),
					'default' => "older",
				),
				'namespace' => array (
					'type' => "namespace",
					'multi' => true,
					'limit' => 50,
				),
				'limit' => array (
					'type' => "limit",
					'max' => 50,
					'default' => 10,
				),
				'prop' => array (
					'type' => array (
						"user",
						"comment",
						"timestamp",
						"patrol",
					),
					'multi' => true,
					'limit' => 50,
				),
			),
		);

		if ( ! $this->is_generator )
			$paramdesc['params']['allrev'] = array (
				'type' => "boolean",
				'default' => false,
			);

		if ( $mwverno >= 11100 )
		{
			$paramdesc['params']['prop']['type'][] = "ids";
			$paramdesc['params']['prop']['type'][] = "title";
			$paramdesc['params']['prop']['type'][] = "flags";
			$paramdesc['params']['prop']['type'][] = "sizes";

			$paramdesc['params']['prop']['default'] = "ids|title|sizes";
		}

		if ( $mwverno >= 11200 )
		{
			$paramdesc['params']['show'] = array (
				'type' => array (
					"minor",
					"!minor",
					"bot",
					"!bot",
					"anon",
					"!anon",
				),
				'multi' => true,
				'limit' => 50,
			);
		}

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['show']['type'][] = "patrolled";
			$paramdesc['params']['show']['type'][] = "!patrolled";
		}

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['prop']['type'][] = "parsedcomment";
			$paramdesc['params']['prop']['type'][] = "notificationtimestamp";

			$paramdesc['params']['user'] = array (
				'type' => "user",
			);
			$paramdesc['params']['excludeuser'] = array (
				'type' => "user",
			);
			$paramdesc['params']['owner'] = array (
				'type' => "user",
			);
			$paramdesc['params']['token'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['prop']['type'][] = "userid";
		}

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['prop']['type'][] = "loginfo";
		}

		return $paramdesc;
	}


}
