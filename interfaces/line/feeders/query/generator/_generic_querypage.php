<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Query Generator Querypage generic feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



abstract class Feeder_Query_Generator_QuerypageGeneric extends
	Feeder_Query_Generator
{

	# ----- Instantiating ----- #

	protected function query_data_key () {
		return $this->queryname();
	}


}
