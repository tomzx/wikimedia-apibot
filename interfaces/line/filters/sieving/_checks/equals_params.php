<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Equals Params checker class
#  (Works with numbers, numeric timestamps, ASCII strings etc.)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_check.php' );


class Checker_Equals_Params extends Checker
{


	# ----- Instantiating ----- #


	public function check ( $element )
	{
		return ( $element === $this->params );
	}


	public function job_name ()
	{
		return "Equals." . (string)$this->params;
	}


}
