<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Debug generic Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );


abstract class Writer_Debug extends Writer
{

	public $print_default = true;
	public $print_data    = false;
	public $print_params  = false;
	public $print_log     = false;


	# ----- Overriding ----- #


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'print_default' );
		$this->_get_param ( $params, 'print_data' );
		$this->_get_param ( $params, 'print_params' );
		$this->_get_param ( $params, 'print_log' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'print_default' );
		$this->_set_param ( $params, 'print_data' );
		$this->_set_param ( $params, 'print_params' );
		$this->_set_param ( $params, 'print_log' );

		return parent::set_params ( $params );
	}


	# ----- Implemented ----- #

	protected function signal_log_slot_name ()
	{
		return "Debug";
	}


	protected function signal_log_job ()
	{
		return array (
			'params' => array (
				'print_default' => $this->print_default,
				'print_data'    => $this->print_data,
				'print_params'  => $this->print_params,
				'print_log'     => $this->print_log,
			),
		);
	}


}
