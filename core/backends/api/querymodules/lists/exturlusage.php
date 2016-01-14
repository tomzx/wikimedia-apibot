<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Exturlusage.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Exturlusage extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "exturlusage";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11100 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "cm",
			'generator' => true,
			'params' => array (
				'prop' => array (
					'type' => array (
						"ids",
						"title",
						"url",
					),
					'multi' => true,
					'default' => "ids|title|url",
					'limit' => 50,
				),
				'query' => array (
					'type' => "string",
				),
				'protocol' => array (
					'type' => array (
						"",
						"http",
						"https",
						"ftp",
						"irc",
						"gopher",
						"telnet",
						"nntp",
						"worldwind",
						"mailto",
						"news",
						"svn",
						"git",
						"mms",
					),
					'default' => "http",
				),
				'namespace' => array (
					'type' => "namespace",
					'multi' => true,
					'limit' => 50,
				),
				'offset' => array (
					'type' => "integer",
				),
				'limit' => array (
					'type' => "limit",
					'max' => 500,
					'default' => 10,
				),
			),
		);

		return $paramdesc;
	}


}
