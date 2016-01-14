<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Set data: From data: Serialize
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_encode.php' );



class Worker_DataElement_Serialize extends Worker_DataElement_GenericEncode
{


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Serialize";
	}


	protected function new_element_type ( &$signal )
	{
		$type = $signal->data_element_type ( $this->from_data_key ( $signal ) );
		return "php-serialized" . substr ( $type, strpos ( $type, '/' ) );
	}


	# ----- Implemented ----- #


	protected function encode ( $element )
	{
		return json_encode ( $element );
	}


}
