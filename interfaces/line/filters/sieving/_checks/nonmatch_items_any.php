<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Any item non-match generic checker class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_match_items.php' );


class Checker_NonMatchItems_Any extends Checker_MatchItems
{


	# ----- Instantiating ----- #


	public function check ( $element )
	{
		foreach ( $this->params as $param )
			if ( ! $this->check_element ( $element, $param ) )
				return true;
		return false;
	}


	public function job_name ()
	{
		return "NonMatchAny";
	}


}
