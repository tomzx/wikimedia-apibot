<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - File multi-lines feeder class
#  (lines ending with '\' are considered continuing on the next line)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Feeder_FileRecords_Multilines extends Feeder_FileRecords_Generic
{

	# ----- Instantiating ----- #

	protected function data_type ()
	{
		return "string/*";
	}

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Multilines";
	}


	protected function read_file_record ( $fp )
	{
		$line = "";
		while ( true )
		{
			$piece = @fgets ( $fp );
			if ( @feof ( $piece ) )
				return $line;
			if ( $piece === false )
				return NULL;
			$line .= $piece;
			if ( substr ( $line, strlen ( $line ) - 1 ) == '\\' )
				$line = substr ( $line, 0, strlen ( $line ) - 1 );
			else
				break;
		}
		return $line;
	}


}
