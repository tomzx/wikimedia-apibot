<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal data element set to a signal paramgroup.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_FromParamgroup extends Worker_DataElement_FromParams
{

	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Group";
	}


	protected function new_element_value ( &$signal )
	{
		$this->die_on_nonstring_property ( 'group' );

		return $signal->paramgroup ( $this->group );
	}


}
