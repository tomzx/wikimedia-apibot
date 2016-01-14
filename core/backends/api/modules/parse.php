<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Parse.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Module_Parse extends API_Module
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "parse";
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11200 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "",
			'readrights' => true,
			'mustbeposted' => true,
			'params' => array (
				'text' => array (
					'type' => "string",
				),
				'title' => array (
					'type' => "string",
					'default' => "API",
				),
				'page' => array (
					'type' => "string",
				),
				'prop' => array (
						"type" => array (
						"text",
						"langlinks",
						"categories",
						"links",
						"templates",
						"images",
						"externallinks",
						"sections",
					),
					'multi' => true,
					'default' => "text|langlinks|categories|links|templates|images|externallinks|sections",
				),
			),
		);

		if ( $mwverno >= 11300 )
		{
			$paramdesc['params']['prop']['type'][] = "revid";
			$paramdesc['params']['prop']['default'] .= "|revid";

			$paramdesc['params']['oldid'] = array (
				'type' => "integer",
			);
		}

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['redirects'] = array (
				'type' => "boolean",
				'default' => false,
			);
			$paramdesc['params']['pst'] = array (
				'type' => "boolean",
				'default' => false,
			);
			$paramdesc['params']['onlypst'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		if ( $mwverno >= 11500 )
		{
			$paramdesc['params']['prop']['type'][] = "displaytitle";
			$paramdesc['params']['prop']['default'] = "|displaytitle";
		}

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['prop']['type'][] = "headitems";
			$paramdesc['params']['prop']['type'][] = "headhtml";

			$paramdesc['params']['summary'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['prop']['type'][] = "iwlinks";
			$paramdesc['params']['prop']['type'][] = "wikitext";
			$paramdesc['params']['prop']['type'][] = "categorieshtml";
			$paramdesc['params']['prop']['type'][] = "languageshtml";

			$paramdesc['params']['pageid'] = array (
				'type' => "integer",
			);
			$paramdesc['params']['uselang'] = array (
				'type' => "string",
			);
			$paramdesc['params']['section'] = array (
				'type' => "string",
			);
			$paramdesc['params']['disablepp'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		return $paramdesc;
	}


}
