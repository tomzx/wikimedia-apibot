<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal custom data element setter class - String - UCFirst
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_String_UCFirst extends Worker_DataElement_String
{


	public $mb = true;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".UCFirst";
	}


	# ----- Implemented ----- #


	protected function modify_element ( $element )
	{
		if ( $this->mb )
			return mb_ucfirst ( $element );
		else
			return ucfirst ( $element );
	}


}
