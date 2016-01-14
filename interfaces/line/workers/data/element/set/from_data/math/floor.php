<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal custom data element setter class - Math - Floor (round down)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_Math_Floor extends Worker_DataElement_Math
{


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Floor";
	}


	# ----- Implemented ----- #


	protected function modify_element ( $element )
	{
		return floor ( $element );
	}


}
