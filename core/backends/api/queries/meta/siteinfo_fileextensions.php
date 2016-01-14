<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: Meta: Siteinfo: Fileextensions.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_siteinfo.php' );


class API_Query_Meta_Siteinfo_Fileextensions extends API_Query_Meta_GenericSiteinfo
{

	# ----- Overriding ----- #

	protected function querykey ()
	{
		return "fileextensions";
	}


}
