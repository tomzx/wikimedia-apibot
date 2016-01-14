<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Set data: From data: Array to dataobject
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_ArrayToDataobject extends Worker_DataElement_Transform
{


	public $dataobject_class;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".ArrayToDataobject";
	}


	protected function new_element_type ( &$signal )
	{
		$type = $signal->data_type ( $this->default_data_key );
		if ( strpos ( $type, 'array' ) === 0 )
			return str_replace ( 'array', 'object', $type );
		else
			return $type;
	}


	# ----- Implemented ----- #


	protected function check_and_modify_element ( $element )
	{
		$this->die_on_nonstring_property ( 'dataobject_class' );

		if ( is_array ( $element ) )
			return new $this->dataobject_class ( $element );
		else
			return $element;
	}


}
