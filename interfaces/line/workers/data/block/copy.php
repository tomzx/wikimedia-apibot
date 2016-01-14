<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal data block key duplicater class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataBlock_Copy extends Worker_DataBlock
{

	public $new_key;


	# ----- Constructor ----- #

	public function __construct ( $core, $new_key = NULL )
	{
		parent::__construct ( $core );
		if ( ! is_null ( $new_key ) )
			$this->new_key = $new_key;
	}


	# ----- Implemented ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Copy";
	}


	# ----- Overriding ----- #


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$signal->copy_data_block ( $this->default_data_key, $this->new_key );

		$extra_params = array (
			'old_key' => $this->default_data_key,
			'new_key' => $this->new_key
		);

		$this->set_jobdata ( $result, $extra_params, array ( "new_key" ) );

		return $result;
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'new_key' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'new_key' );

		return parent::set_params ( $params );
	}


}
