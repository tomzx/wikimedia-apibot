<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules - Paraminfo.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Module_Paraminfo extends API_Module
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "paraminfo";
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11200 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "",
			'params' => array (
				'modules' => array (
					'type' => array (
						"query",
						"login",
						"logout",
						"move",
						"delete",
						"undelete",
						"rollback",
						"protect",
						"block",
						"unblock",
						"expandtemplates",
						"parse",
					),
					'multi' => true,
				),
				'querymodules' => array (
					'type' => array (
						"info",
						"revisions",
						"links",
						"langlinks",
						"images",
						"imageinfo",
						"stashimageinfo",
						"templates",
						"categories",
						"extlinks",
						"globalusage",
						"allcategories",
						"allimages",
						"alllinks",
						"allpages",
						"allusers",
						"backlinks",
						"blocks",
						"categorymembers",
						"deletedrevs",
						"embeddedin",
						"exturlusage",
						"imageusage",
						"logevents",
						"random",
						"recentchanges",
						"search",
						"tags",
						"usercontribs",
						"watchlist",
						"watchlistraw",
						"users",
						"globalblocks",
						"allmessages",
						"globaluserinfo",
						"siteinfo",
						"userinfo",
					),
					'multi' => true,
				),
			),
		);

		if ( $mwverno >= 11300 )
		{
			$paramdesc['params']['modules']['type'][] = "edit";
			$paramdesc['params']['modules']['type'][] = "watch";
			$paramdesc['params']['querymodules']['type'][] = "categoryinfo";
			$paramdesc['params']['querymodules']['type'][] = "watchlistraw";
		}

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['modules']['type'][] = "purge";
			$paramdesc['params']['modules']['type'][] = "emailuser";
			$paramdesc['params']['modules']['type'][] = "patrol";
			$paramdesc['params']['querymodules']['type'][] = "duplicatefiles";
		}

		if ( $mwverno >= 11500 )
		{
			$paramdesc['params']['mainmodule'] = array (
				'type' => "string",
			);
			$paramdesc['params']['pagesetmodule'] = array (
				'type' => "string",
			);
			$paramdesc['params']['modules']['type'][] = "import";
			$paramdesc['params']['querymodules']['type'][] = "protectedtitles";
			$paramdesc['params']['querymodules']['type'][] = "tags";
		}

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['modules']['type'][] = "userrights";
			$paramdesc['params']['modules']['type'][] = "upload";
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['querymodules']['type'][] = "pageprops";
			$paramdesc['params']['querymodules']['type'][] = "filearchive";  // may require rights for deleted files
			$paramdesc['params']['querymodules']['type'][] = "iwbacklinks";
			$paramdesc['params']['querymodules']['type'][] = "iwlinks";
		}

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['querymodules']['type'][] = "querypage";
		}

		if ( $mwverno >= 12200 )
		{
			$paramdesc['params']['querymodules']['type'][] = "filerepoinfo";
		}

		return $paramdesc;
	}


}
