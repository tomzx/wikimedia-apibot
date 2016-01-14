<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic redirect pages (by page text) filter class
#
#  Does not use the checker object extension!
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


abstract class Filter_Page_Text_Redirect extends
	Filter_Page_Text
{

	# ----- Overriding ----- #

	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".Redirect";
	}


	protected function element_to_check ( &$signal )
	{
		$page = parent::element_to_check ( $signal );

		if ( is_null ( $page ) )
			return NULL;

		return ( $page->is_redirect() );
	}


}
