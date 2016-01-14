<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - New pages (by page info property) generic filter class
#
#  Does not use the checker object extension!
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );


abstract class Filter_Page_New extends Filter_Page
{

	# ----- Constructor ----- #

	function __construct ( $core, $checker_params = NULL,
		$fetch_page_properties = NULL )
	{
		$this->data_property = "new";
		parent::__construct ( $core, $checker_params, $fetch_page_properties );
	}


	# ----- Instantiating ----- #

	protected function job_params ()
	{
		return array();
	}

	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".New";
	}


}
