<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Set data: From data: Dataobject to array
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_DataobjectToArray extends Worker_DataElement_Transform
{


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".DataobjectToArray";
	}


	protected function new_element_type ( &$signal )
	{
		$type = $signal->data_type ( $this->default_data_key );
		return str_replace ( 'object', 'array', $type );
	}


	# ----- Implemented ----- #


	protected function check_and_modify_element ( $element )
	{
		if ( $element instanceof Dataobject )
			return $element->data();
		else
		{
			return (array)$element;
		}
	}


}
