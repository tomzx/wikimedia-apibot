<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Data block: Implode a string into an array.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_Implode extends Worker_DataElement_Transform
{


	public $delimiter = "";


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Implode";
	}


	# ----- Implemented ----- #


	protected function check_and_modify_element ( $element )
	{
		$this->die_on_nonstring_property ( 'delimiter' );

		if ( is_object ( $element ) )
			if ( $element instanceof Dataobject )
				$element = $element->data();
			else
				$element = (array)$element;

		if ( ! is_array ( $element ) )
		{
			$this->log ( "The element to implode is not an array or object - cannot proceed!",
				LL_ERROR );
			return NULL;
		}

		return implode ( $this->delimiter, $element );
	}


}
