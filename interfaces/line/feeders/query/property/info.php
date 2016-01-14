<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework: Page properties: Page general info feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Feeder_Query_Property_Info extends Feeder_Query_Property
{

	# ----- Constructor ----- #

	function __construct ( $core )
	{
		parent::__construct ( $core );
		unset ( $this->pagedata );
	}


	# ----- Instantiating ----- #

	protected function data_type ()
	{
		return "array/pageinfo.element";
	}


	protected function query ( &$core )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../../../../core/queries/property/info.php' );
		return new Query_Property_Info ( $core );
	}


}
