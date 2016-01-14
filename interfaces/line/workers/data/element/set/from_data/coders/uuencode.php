<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Set data: From data: UUencode
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_encode.php' );



class Worker_DataElement_UUencode extends Worker_DataElement_GenericEncode
{


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".UUencode";
	}


	protected function new_element_type ( &$signal )
	{
		$type = $signal->data_element_type ( $this->from_data_key ( $signal ) );
		return "uuencoded" . substr ( $type, strpos ( $type, '/' ) );
	}


	# ----- Implemented ----- #


	protected function encode ( $element )
	{
		if ( ! is_string ( $element ) )
		{
			$this->log ( "The element to uuencode is not a string - cannot proceed!",
				LL_ERROR );
			return NULL;
		}

		$result = @convert_uuencode ( $element );
		if ( $result === false )
			return NULL;

		return $result;
	}


}
