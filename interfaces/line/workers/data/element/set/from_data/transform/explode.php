<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Data block: Explode a string into an array.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_Explode extends Worker_DataElement_Transform
{


	public $delimiter;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Explode";
	}


	# ----- Implemented ----- #


	protected function check_and_modify_element ( $element )
	{
		$this->die_on_nonstring_property ( 'delimiter' );

		if ( ! is_string ( $element ) )
		{
			$this->log ( "The element to explode is not a string - cannot proceed!",
				LL_ERROR );
			return NULL;
		}

		return explode ( $this->delimiter, $element );
	}


}
