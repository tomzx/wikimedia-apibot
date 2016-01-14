<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Meta: Siteinfo.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Meta_Siteinfo extends API_Params_Query_Meta
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "siteinfo";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 10800 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "si",
			'params' => array (
				'prop' => array (
					'type' => array (
						"general",
						"namespaces",
					),
					'default' => "general",
				),
			),
		);

		if ( $mwverno >= 11100 )
		{
			$paramdesc['params']['prop']['type'][] = "statistics";
			$paramdesc['params']['prop']['type'][] = "dbrepllag";
			$paramdesc['params']['prop']['type'][] = "interwikimap";

			$paramdesc['params']['filteriw'] = array (
				'type' => array (
					"local",
					"!local",
				),
			);
			$paramdesc['params']['showalldb'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		if ( $mwverno >= 11300 )
		{
			$paramdesc['params']['prop']['type'][] = "namespacealiases";
			$paramdesc['params']['prop']['type'][] = "specialpagealiases";
			$paramdesc['params']['prop']['type'][] = "usergroups";
		}

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['prop']['type'][] = "extensions";
			$paramdesc['params']['prop']['type'][] = "magicwords";
		}

		if ( $mwverno >= 11500 )
		{
			$paramdesc['params']['prop']['type'][] = "fileextensions";
			$paramdesc['params']['prop']['type'][] = "rightsinfo";
		}

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['prop']['type'][] = "languages";
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['numberingroup'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		return $paramdesc;
	}


}
