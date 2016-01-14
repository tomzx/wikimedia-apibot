<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - File lines feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Feeder_FileRecords_Lines extends Feeder_FileRecords_Generic
{

	# ----- Instantiating ----- #

	protected function data_type ()
	{
		return "string/*";
	}

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Lines";
	}


	protected function read_file_record ( $fp )
	{
		return @fgets ( $fp );
	}


}
