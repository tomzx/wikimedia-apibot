<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Usercontribs.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Usercontribs extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "usercontribs";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 10900 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "uc",
			'params' => array (
				'user' => array (
					'type' => "string",
					'multi' => true,
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
				'limit' => array (
					'type' => "limit",
					'max' => 500,
					'default' => 10,
				),
			),
		);

		if ( $mwverno >= 11100 )
		{
			$paramdesc['params']['namespace'] = array (
				'type' => "namespace",
				'multi' => true,
				'limit' => 50,
			);
			$paramdesc['params']['prop'] = array (
				'type' => array (
					"ids",
					"title",
					"timestamp",
					"comment",
					"flags",
				),
				'multi' => true,
				'default' => "ids|title|timestamp|comment|flags",
				'limit' => 50,
			);
			$paramdesc['params']['show'] = array (
				'type' => array (
					"minor",
					"!minor",
				),
				'multi' => true,
				'limit' => 50,
			);
		}

		if ( $mwverno >= 11300 )
		{
			$paramdesc['params']['userprefix'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['continue'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11500 )
		{
			$paramdesc['params']['prop']['type'] = array();

			$paramdesc['params']['show']['type'][] = "patrolled";
			$paramdesc['params']['show']['type'][] = "!patrolled";
		}

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['prop']['type'][] = "parsedcomment";
			$paramdesc['params']['prop']['type'][] = "tags";

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
