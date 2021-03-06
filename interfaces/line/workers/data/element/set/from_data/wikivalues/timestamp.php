<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal data element set to the timestamp it contains,
#  as returned by the signal data_timestamp() function.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_FromTimestamp
	extends Worker_DataElement_FromData_WikiValues
{


	# ----- Constructor ----- #


	public function __construct ( $core )
	{
		parent::__construct ( $core, "string/timestamp" );
	}


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Timestamp";
	}


	# ----- Implementing ----- #


	protected function new_element_value ( &$signal )
	{
		return $signal->data_timestamp ( $this->from_data_key ( $signal ) );
	}


}
