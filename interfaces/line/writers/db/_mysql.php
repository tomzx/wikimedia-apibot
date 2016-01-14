<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic MySQL database writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../../../libs/db/mysql.php' );


abstract class Writer_MySQL extends Writer_DB
{

	# ----- Implemented ----- #


	protected function db_object ( $core )
	{
		return new Database_MySQL ( $core );
	}


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".MySQL";
	}


	# insert_record(), update_record() and exists_record() are still abstract


}
