<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Set data: From data: String CRC32
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_CRC32 extends Worker_DataElement_Checksum
{


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".CRC32";
	}


	# ----- Implemented ----- #


	protected function checksum ( $element )
	{
		return crc32 ( $element );
	}


}
