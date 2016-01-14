<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Recentchanges.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Recentchanges extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "recentchanges";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 10900 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "rc",
			'generator' => true,
			'params' => array (
				'prop' => array (
					'type' => array (
						"user",
						"comment",
						"flags",
					),
					'multi' => true,
					'limit' => 50,
				),
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
				'show' => array (
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
				),
				'limit' => array (
					'type' => "limit",
					'max' => 500,
					'default' => 10,
				),
			),
		);

		if ( $mwverno >= 11100 )
		{
			$paramdesc['params']['prop']['type'][] = "timestamp";
			$paramdesc['params']['prop']['type'][] = "title";
			$paramdesc['params']['prop']['type'][] = "ids";
			$paramdesc['params']['prop']['type'][] = "sizes";

			$paramdesc['params']['prop']['default'] = "title|timestamp|ids";
		}

		if ( $mwverno >= 11200 )
		{
			$paramdesc['params']['type'] = array (
				'type' => array (
					"edit",
					"new",
					"log",
				),
				'multi' => true,
				'limit' => 50,
			);
		}

		if ( $mwverno >= 11300 )
		{
			$paramdesc['params']['prop']['type'][] = "redirect";
			$paramdesc['params']['prop']['type'][] = "patrolled";

			$paramdesc['params']['show']['type'][] = "redirect";
			$paramdesc['params']['show']['type'][] = "!redirect";
			$paramdesc['params']['show']['type'][] = "patrolled";
			$paramdesc['params']['show']['type'][] = "!patrolled";
		}

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['prop']['type'][] = "loginfo";

			$paramdesc['params']['token'] = array (
				'type' => array (
					"patrol",
				),
				'multi' => true,
				'limit' => 50,
			);
		}

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['prop']['type'][] = "parsedcomment";
			$paramdesc['params']['prop']['type'][] = "tags";

			$paramdesc['params']['user'] = array (
				'type' => "user",
			);
			$paramdesc['params']['excludeuser'] = array (
				'type' => "user",
			);
			$paramdesc['params']['tag'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['prop']['type'][] = "userid";
		}

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['toponly'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		return $paramdesc;
	}


}
