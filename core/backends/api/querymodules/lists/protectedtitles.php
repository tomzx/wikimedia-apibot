<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Protectedtitles.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Protectedtitles extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "protectedtitles";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11500 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "pt",
			'generator' => true,
			'params' => array (
				'namespace' => array (
					'type' => "namespace",
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
				'limit' => array (
					'type' => "limit",
					'max' => 500,
					'default' => 10,
				),
				'prop' => array (
					'type' => array (
						"timestamp",
						"user",
						"comment",
						"expiry",
						"level",
					),
					'multi' => true,
					'default' => "timestamp|level",
					'limit' => 50,
				),
				'level' => array (
					'type' => array (
						"autoconfirmed",
						"sysop",
					),
					'multi' => true,
					'limit' => 50,
				),
			),
		);

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['prop']['type'][] = "parsedcomment";
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['prop']['type'][] = "userid";
		}

		return $paramdesc;
	}


}
