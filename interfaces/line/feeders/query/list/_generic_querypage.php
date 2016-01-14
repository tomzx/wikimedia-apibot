<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Query List Querypage generic feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Feeder_Query_List_QuerypageGeneric extends Feeder_Query_List
{

	# ----- Instantiating ----- #

	protected function data_type ()
	{
		return "array/page";
	}


	protected function query_data_key () {
		return $this->queryname();
	}


}
