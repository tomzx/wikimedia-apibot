<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework: Page properties: External links feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Feeder_Query_Property_Extlinks extends Feeder_Query_Property
{

	# ----- Instantiating ----- #

	protected function data_type ()
	{
		return "array/extlink";
		# the parent class converts string values to arrays
	}


	protected function query ( &$core )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../../../../core/queries/property/extlinks.php' );
		return new Query_Property_Extlinks ( $core );
	}


}
