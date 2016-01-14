<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal data block Set worker class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataBlock_Set extends Worker_DataBlock
{

	public $data_block;


	# ----- Implemented ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Set";
	}


	# ----- Overriding ----- #

	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$this->data_block = $this->data_block ( $signal );
		$signal->set_data_block ( $this->default_data_key, $this->data_block );

		$this->set_jobdata ( $result, array(), array ( "data_block" ) );

		return $result;
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'data_block' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'data_block' );

		return parent::set_params ( $params );
	}


	# ----- Overridable ----- #

	protected function data_block ( &$signal )
	{
		return $this->data_block;
	}


}
