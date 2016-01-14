<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework: Page properties: Page content properties feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Feeder_Query_Property_Pageprops extends Feeder_Query_Property
{

	# ----- Instantiating ----- #

	protected function data_type ()
	{
		return "array/pageprop";
	}


	protected function query ( &$core )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../../../../core/queries/property/pageprops.php' );
		return new Query_Property_Pageprops ( $core );
	}


}
