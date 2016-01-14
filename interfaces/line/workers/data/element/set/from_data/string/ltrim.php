<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal custom data element setter class - String - LTrim
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_String_LTrim extends Worker_DataElement_String
{


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".LTrim";
	}


	# ----- Implemented ----- #


	protected function modify_element ( $element )
	{
		return ltrim ( $element );
	}


}
