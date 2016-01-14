<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Set data: From data: Unserialize
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_decode.php' );



class Worker_DataElement_Unserialize extends Worker_DataElement_GenericDecode
{


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Unserialize";
	}


	# ----- Implemented ----- #


	protected function decode ( $element )
	{
		$result = @unserialize ( $element );
		if ( $result === false )
			return NULL;

		return $result;
	}


}
