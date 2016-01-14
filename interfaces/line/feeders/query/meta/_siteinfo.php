<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Siteinfo Meta feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



abstract class Feeder_Query_GenericSiteinfo extends Feeder_Query_Meta
{

	# ----- Instantiating ----- #

	public function queryname ()
	{
		return "siteinfo";
	}


}
