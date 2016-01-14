<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Allpages.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Allpages extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "allpages";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 10900 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "ap",
			'generator' => true,
			'params' => array (
				'from' => array (
					'type' => "string",
				),
				'prefix' => array (
					'type' => "string",
				),
				'namespace' => array (
					'type' => "namespace",
				),
				'filterredir' => array (
					'type' => array (
						"all",
						"redirects",
						"nonredirects",
					),
					'default' => "all",
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
			$paramdesc['params']['minsize'] = array (
				'type' => "integer",
			);
			$paramdesc['params']['maxsize'] = array (
				'type' => "integer",
			);
			$paramdesc['params']['prtype'] = array (
				'type' => array (
					"edit",
					"move",
				),
				'multi' => true,
				'limit' => 50,
			);
			$paramdesc['params']['prlevel'] = array (
				'type' => array (
					"",
					"autoconfirmed",
					"sysop",
				),
				'multi' => true,
				'limit' => 50,
			);
		}

		if ( $mwverno >= 11200 )
		{
			$paramdesc['params']['dir'] = array (
				'type' => array (
					"ascending",
					"descending",
				),
				'default' => "ascending",
			);
			$paramdesc['params']['filterlanglinks'] = array (
				'type' => array (
					"withlanglinks",
					"withoutlanglinks",
					"all",
				),
				'default' => "all",
			);
		}

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['prfiltercascade'] = array (
				'type' => array (
					"cascading",
					"noncascading",
					"all",
				),
				'default' => "all",
			);
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['to'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['prexpiry'] = array (
				'type' => array (
					"indefinite",
					"definite",
					"all",
				),
				'default' => "all",
			);
		}

		return $paramdesc;
	}


}
