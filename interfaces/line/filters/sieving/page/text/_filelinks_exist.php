<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Filelinks exist in pages text generic filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


abstract class Filter_Page_Text_CheckFilelinksExistence extends
	Filter_Page_Text
{

	# ----- Constructor ----- #

	function __construct ( $core, $checker_params = NULL,
		$fetch_page_properties = NULL )
	{
		if ( is_array ( $checker_params ) && (
			isset ( $checker_params['wiki'] ) ||
			isset ( $checker_params['namespace'] ) ||
			isset ( $checker_params['name'] )
		) )
			$checker_params = array ( $checker_params );
		parent::__construct ( $core, $checker_params, $fetch_page_properties );
	}


	# ----- Instantiating ----- #

	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".Filelinks";
	}


	# ----- New (to be used in descendants) ----- #

	public function filelink_exists ( $page, $filelink )
	{
		return $page->filelink_exists ( $filelink );
	}


}
