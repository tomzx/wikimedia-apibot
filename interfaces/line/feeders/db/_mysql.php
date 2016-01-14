<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic MySQL database feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../../../libs/db/mysql.php' );


abstract class Feeder_DB_MySQL extends Feeder_DB
{


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".MySQL";
	}


	# ----- Implemented ----- #


	protected function db_object ( $core )
	{
		return new Database_MySQL ( $core );
	}


}
