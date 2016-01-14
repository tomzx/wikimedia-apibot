<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal data element set to the rcid it contains,
#  as returned by the signal data_rcid() function.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_FromRcid
	extends Worker_DataElement_FromData_WikiValues
{


	# ----- Constructor ----- #


	public function __construct ( $core )
	{
		parent::__construct ( $core, "numeric/rcid" );
	}


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Rcid";
	}


	# ----- Implementing ----- #


	protected function new_element_value ( &$signal )
	{
		return $signal->data_rcid ( $this->from_data_key ( $signal ) );
	}


}
