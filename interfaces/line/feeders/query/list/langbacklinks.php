<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Langbacklinks List feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Feeder_Query_List_Langbacklinks extends Feeder_Query_List
{

	# ----- Instantiating ----- #

	protected function data_type ()
	{
		return "array/page";
	}


	protected function query ( &$core )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../../../../core/queries/list/langbacklinks.php' );
		return new Query_List_Langbacklinks ( $core );
	}


}
