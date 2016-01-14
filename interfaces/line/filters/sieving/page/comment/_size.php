<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Page comment size generic filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


abstract class Filter_Page_Comment_Size extends Filter_Page_Comment
{


	# ----- Overriding ----- #

	protected function element_property ( $element, $property )
	{
		return mb_strlen ( parent::element_property ( $element, $property ) );
	}


}
