<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Search.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Search extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "search";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11100 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "sr",
			'generator' => true,
			'params' => array (
				'search' => array (
					'type' => "string",
				),
				'namespace' => array (
					'type' => "namespace",
					'multi' => true,
					'default' => 0,
					'limit' => 50,
				),
				'what' => array (
					'type' => array (
						"title",
						"text",
					),
					'default' => "title",
				),
				'redirects' => array (
					'type' => "boolean",
					'default' => false,
				),
				'offset' => array (
					'type' => "integer",
				),
				'limit' => array (
					'type' => "limit",
					'max' => 50,
					'default' => 10,
				),
			),
		);

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['info'] = array (
				'type' => array (
					"totalhits",
					"suggestion",
				),
				'multi' => true,
				'default' => "totalhits|suggestion",
			);
			$paramdesc['params']['prop'] = array (
				'type' => array (
					"size",
					"wordcount",
					"timestamp",
					"snippet",
				),
				'multi' => true,
				'default' => "size|wordcount|timestamp|snippet",
			);
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['what']['type'][] = "nearmatch";

			$paramdesc['params']['prop']['type'][] = "score";
			$paramdesc['params']['prop']['type'][] = "titlesnippet";
			$paramdesc['params']['prop']['type'][] = "redirectsnippet";
			$paramdesc['params']['prop']['type'][] = "redirecttitle";
			$paramdesc['params']['prop']['type'][] = "sectionsnippet";
			$paramdesc['params']['prop']['type'][] = "sectiontitle";
			$paramdesc['params']['prop']['type'][] = "hasrelated";
		}

		return $paramdesc;
	}


}
