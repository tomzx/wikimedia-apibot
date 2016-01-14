<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Data element: Set: From a value.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_FromValue extends Worker_DataElement_Set
{

	public $value;


	# ----- Constructor ----- #


	public function __construct ( $core, $value = NULL )
	{
		parent::__construct ( $core );
		if ( ! is_null ( $value ) )
			$this->value = $value;
	}


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Value";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'value' );

		return $params;
	}


	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'value' );

		return parent::set_params ( $params );
	}


	protected function new_element_value ( &$signal )
	{
		$this->die_on_unset_property ( 'value' );

		return $this->value;
	}


}
