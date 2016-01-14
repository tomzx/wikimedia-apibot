<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Querypage.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Querypage extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "querypage";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11800 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "qp",
			'generator' => true,
			'params' => array (
				'page' => array (
					'type' => array (
						"Ancientpages",
						"BrokenRedirects",
						"Deadendpages",
						"Disambiguations",
						"DoubleRedirects",
						"Listredirects",
						"Lonelypages",
						"Longpages",
						"Mostcategories",
						"Mostimages",
						"Mostlinkedcategories",
						"Mostlinkedtemplates",
						"Mostlinked",
						"Mostrevisions",
						"Fewestrevisions",
						"Shortpages",
						"Uncategorizedcategories",
						"Uncategorizedpages",
						"Uncategorizedimages",
						"Uncategorizedtemplates",
						"Unusedcategories",
						"Unusedimages",
						"Wantedcategories",
						"Wantedfiles",
						"Wantedpages",
						"Wantedtemplates",
						"Unwatchedpages",
						"Unusedtemplates",
						"Withoutinterwiki",
					),
					'required' => true,
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
