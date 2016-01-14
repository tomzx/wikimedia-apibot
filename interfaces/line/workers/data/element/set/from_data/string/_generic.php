<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic signal data element modifier class - String
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Worker_DataElement_String extends Worker_DataElement_FromData
{


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".String";
	}


	# ----- Implemented ----- #


	protected function check_and_modify_element ( $element )
	{
		if ( ! is_string ( $element ) )
		{
			$this->log ( 'The data is not string - cannot proceed!',
				LL_ERROR );
			return NULL;
		}

		return $this->modify_element ( $element );
	}


	# ----- Abstract ----- #


	abstract protected function modify_element ( $element );


}
