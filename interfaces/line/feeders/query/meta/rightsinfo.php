<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Rightsinfo siteinfo Meta feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_siteinfo.php' );



class Feeder_Query_Siteinfo_Rightsinfo extends Feeder_Query_GenericSiteinfo
{

	# ----- Instantiating ----- #

	protected function data_type ()
	{
		return "string/rightsinfo.element";
	}

	protected function query ( &$core )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../../../../core/queries/meta/siteinfo_rightsinfo.php' );
		return new Query_Meta_Siteinfo_Rightsinfo ( $core );
	}


}
