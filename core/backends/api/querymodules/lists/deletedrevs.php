<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Deletedrevs.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Deletedrevs extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "deletedrevs";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11200 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "dr",
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
					'default' => 0,
				),
				'prop' => array (
					'type' => array (
						"revid",
						"user",
						"comment",
						"minor",
						"len",
						"content",
						"token",
					),
					'multi' => true,
					'default' => "user|comment",
					'limit' => 50,
				),
				'limit' => array (
					'type' => "limit",
					'max' => 500,
					'default' => 10,
				),
			),
		);

		if ( $mwverno >= 11500 )
		{
			$paramdesc['params']['from'] = array (
				'type' => "string",
			);
			$paramdesc['params']['user'] = array (
				'type' => "user",
			);
			$paramdesc['params']['excludeuser'] = array (
				'type' => "user",
			);
			$paramdesc['params']['continue'] = array (
				'type' => "string",
			);
			$paramdesc['params']['unique'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['prop']['type'][] = "parsedcomment";
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['prop']['type'][] = "userid";
			$paramdesc['params']['prop']['type'][] = "minor";
		}

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['prop']['type'][] = "parentid";
			$paramdesc['params']['prop']['type'][] = "len";

			$paramdesc['params']['to'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11900 )
		{
			$paramdesc['params']['prop']['type'][] = "sha1";
		}

		return $paramdesc;
	}


}
