<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Wikilinks exist in pages text generic filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


abstract class Filter_Page_Text_CheckWikilinksExistence extends
	Filter_Page_Text
{

	# ----- Constructor ----- #

	function __construct ( $core, $checker_params = NULL,
		$fetch_page_properties = NULL )
	{
		if ( is_array ( $checker_params ) && (
			isset ( $checker_params['colon'] ) ||
			isset ( $checker_params['wiki'] ) ||
			isset ( $checker_params['namespace'] ) ||
			isset ( $checker_params['name'] ) ||
			isset ( $checker_params['anchor'] ) ||
			isset ( $checker_params['text'] )
		) )
			$checker_params = array ( $checker_params );
		parent::__construct ( $core, $checker_params, $fetch_page_properties );
	}


	# ----- Instantiating ----- #

	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".Wikilinks";
	}


	# ----- New (to be used in descendants) ----- #

	public function wikilink_exists ( $page, $wikilink )
	{
		return $page->wikilink_exists ( $wikilink );
	}


}
