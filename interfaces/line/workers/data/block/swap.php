<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal data block key swap worker class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataBlock_Swap extends Worker_DataBlock
{

	public $key2;


	# ----- Constructor ----- #

	public function __construct ( $core, $key2 = NULL )
	{
		parent::__construct ( $core );
		if ( ! is_null ( $key2 ) )
			$this->key2 = $key2;
	}


	# ----- Implemented ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Swap";
	}


	# ----- Overriding ----- #


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$signal->swap_data_blocks ( $this->default_data_key, $this->key2 );

		$extra_params = array (
			'key1' => $this->default_data_key,
			'key2' => $this->key2,
		);

		$this->set_jobdata ( $result, $extra_params, array ( "key2" ) );

		return $result;
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'key2' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'key2' );

		return parent::set_params ( $params );
	}


}
