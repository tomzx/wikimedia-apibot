<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: Meta: General Siteinfo (a non-abstract form of GenericSiteinfo)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_siteinfo.php' );


class API_Query_Meta_Siteinfo extends API_Query_Meta_GenericSiteinfo
{

	protected $propname;

	// no default params can be specified

	# ----- Constructor ----- #


	function __construct ( $propname, $backend, $settings = array(),
		$defaults = array() )
	{
		$this->propname = $propname;
		parent::__construct ( $backend, $settings, $defaults );
	}


	# ----- Overriding ----- #

	protected function querykey ()
	{
		return $this->propname;
	}


}
