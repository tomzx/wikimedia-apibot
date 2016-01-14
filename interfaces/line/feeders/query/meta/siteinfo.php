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



class Feeder_Query_Siteinfo extends Feeder_Query_Meta
{

	# ----- Constructor ----- #

	function __construct ( $propname, $core )
	{
		$this->prop = $propname;
		parent::__construct ( $core );
	}


	# ----- Instantiating ----- #

	protected function data_type ()
	{
		return "array/siteinfo.element";
	}


	protected function query ( &$core )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../../../../core/queries/meta/siteinfo.php' );
		return new Query_Meta_Siteinfo ( $this->prop, $this->core );
	}


}
