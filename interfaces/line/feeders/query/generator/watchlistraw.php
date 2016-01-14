<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Watchlistraw Generator feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Feeder_Query_Generator_Watchlistraw extends Feeder_Query_Generator
{

	# ----- Instantiating ----- #

	protected function query ( &$core )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../../../../core/queries/generator/watchlistraw.php' );
		return new Query_Generator_Watchlistraw ( $core );
	}


}
