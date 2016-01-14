<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Regexes match page text filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


abstract class Filter_Page_Text_CheckRegexesExistence extends
	Filter_Page_Text
{

	# ----- Constructor ----- #

	function __construct ( $core, $checker_params = NULL,
		$fetch_page_properties = NULL )
	{
		if ( ! is_array ( $checker_params ) )
			$checker_params = array ( $checker_params );
		parent::__construct ( $core, $checker_params, $fetch_page_properties );
	}


	# ----- Instantiating ----- #

	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".Regexes";
	}


	# ----- New (to be used in descendants) ----- #

	public function regex_exists ( $page, $regex )
	{
		return $page->regex_exists ( $regex );
	}


}
