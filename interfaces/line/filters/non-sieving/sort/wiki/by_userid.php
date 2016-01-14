<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Filters: Sort data by ByUserid.
#
#  WARNING: Can hog a LOT of memory - be careful what feed you run it over!
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );


class Filter_Sort_ByUserid extends Filter_Sort
{


	# ----- Overridable ----- #


	protected function sortkey ( &$signal )
	{
		return $signal->data_by_userid ( $this->default_data_key );
	}


}
