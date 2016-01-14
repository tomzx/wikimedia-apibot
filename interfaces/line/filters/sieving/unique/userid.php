<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Filters: Pass only Unique (by data userid) data.
#
#  Override id_string() to create your own criteria for uniqueness.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Filter_Unique_Userid extends Filter_Unique
{


	# ----- Overriding ----- #


	protected function element_to_check ( &$signal )
	{
		return $signal->data_userid ( $this->default_data_key );
	}


	protected function slotname_postfix ()
	{
		return "Userid";
	}


}
