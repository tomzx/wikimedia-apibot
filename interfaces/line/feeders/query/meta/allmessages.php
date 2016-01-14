<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Allmessages Meta feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Feeder_Query_Allmessages extends Feeder_Query_Meta
{

	# ----- Constructor ----- #

	function __construct ( $core )
	{
		parent::__construct ( $core );
		$this->messages = "*";
	}


	# ----- Instantiating ----- #

	protected function data_type ()
	{
		return "array/mwmessage";
	}


	protected function query ( &$core )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../../../../core/queries/meta/allmessages.php' );
		return new Query_Meta_Allmessages ( $core );
	}


}
