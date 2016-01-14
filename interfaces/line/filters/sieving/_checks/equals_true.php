<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Equals True checker class
#  (Works with numbers, numeric timestamps, ASCII strings etc.)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_check.php' );


class Checker_Equals_True extends Checker
{


	# ----- Instantiating ----- #


	public function check ( $element )
	{
		return ( $element === true );
	}


	public function job_name ()
	{
		return "Equals.True";
	}


}
