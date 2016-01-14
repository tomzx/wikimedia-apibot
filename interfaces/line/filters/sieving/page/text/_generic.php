<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Page texts (and their elements) generic filter class
#
#  Does not use the checker object extension!
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );


abstract class Filter_Page_Text extends Filter_Page
{

	# ----- Constructor ----- #

	function __construct ( $core, $checker_params = NULL,
		$fetch_page_properties = NULL )
	{
		$this->data_property = "text";
		parent::__construct ( $core, $checker_params, $fetch_page_properties );
	}


	# ----- Instantiating ----- #

	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".Text";
	}


	# ----- Overriding ----- #

	protected function element_to_check ( &$signal )
	{
		// shunts the parent function!
		$page = $signal->data_element ( $this->default_data_key );

		if ( is_array ( $page ) )
			$page = new Page ( $this->core, $page );

		if ( is_object ( $page ) )
			if ( isset ( $page->text ) )
				return $page;

		return NULL;
	}


}
