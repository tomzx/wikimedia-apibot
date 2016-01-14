<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic signal data element setting from data class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Worker_DataElement_FromData extends Worker_DataElement_Set
{


	public $from_data_key;


	public $from_sublevels = array();  // or array with the subs names as values


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".FromData";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'from_data_key' );
		$this->_get_param ( $params, 'from_sublevels' );

		return $params;
	}


	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'from_data_key' );
		$this->_set_param ( $params, 'from_sublevels' );

		return parent::set_params ( $params );
	}


	protected function new_element_value ( &$signal )
	{
		$element = get_subelement (
			$signal->data_element ( $this->from_data_key ( $signal ) ),
				$this->from_sublevels );

		return $this->check_and_modify_element ( $element );
	}


	protected function new_element_type ( &$signal )
	{
		return ( isset ( $this->new_element_type )
			? $this->new_element_type
			: $signal->data_type ( $this->from_data_key ( $signal ) ) );
	}


	# ----- New ----- #


	protected function from_data_key ( &$signal )
	{
		return ( isset ( $this->from_data_key )
			? $this->from_data_key
			: $this->default_data_key );
	}


	# ----- Abstract ----- #


	abstract protected function check_and_modify_element ( $element );


}
