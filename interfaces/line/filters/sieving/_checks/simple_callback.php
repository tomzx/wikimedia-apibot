<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Simple callback-based checker class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_check_withcallback.php' );


class Checker_WithSimpleCallback extends Checker_WithCallback
{


	# ----- Instantiating ----- #


	public function check ( $element )
	{
		return $this->check_element ( $element, $this->params );
	}


	public function job_name ()
	{
		return "";
	}


}
