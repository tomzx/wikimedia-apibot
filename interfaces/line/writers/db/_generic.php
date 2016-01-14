<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic database writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );


define ( 'DB_WM_INSERT', 1 );
define ( 'DB_WM_UPDATE', 2 );
define ( 'DB_WM_INSUPD', 3 );


abstract class Writer_DB extends Writer
{

	protected $db;

	public $db_params;

	public $write_mode = DB_WM_INSERT;


	# ----- Constructor ----- #


	function __construct ( $core, $start_params = array() )
	{
		parent::__construct ( $core );

		$this->db = $this->db_object ( $core );

		if ( isset ( $start_params['db'] ) )
			$this->db_params = $start_params['db'];
	}


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return "DB";
	}


	protected function signal_log_job ()
	{
		$jobdesc = array ( 'params' => array() );
		$jobdesc['params']['db_params' ] = $this->db_params;
		$jobdesc['params']['write_mode'] = $this->write_mode;
		return $jobdesc;
	}



	protected function process_start ( &$signal )
	{
		$result = parent::process_start ( $signal );
		if ( ! $this->db->connect ( $this->db_params ) )
		{
			$this->log ( "Could not connect to database " . $this->db_params['name'] );
			return NULL;
		}
		return $result;
	}


	protected function process_data ( &$signal )
	{
		parent::process_data ( $signal );

		$record_array = $this->db_record_array (
			$signal->data_element ( $this->default_data_key )
		);

		$this->set_jobdata ( true, array(), array ( 'db_params' ) );

		switch ( $this->write_mode )
		{
			case DB_WM_INSERT :
				return $this->insert_record ( $record_array );
			case DB_WM_UPDATE :
				return $this->update_record ( $record_array );
			case DB_WM_INSUPD :
				if ( $this->exists_record ( $record_array ) )
					return $this->update_record ( $record_array );
				else
					return $this->insert_record ( $record_array );
		}

	}


	protected function process_end ( &$signal )
	{
		$this->db->disconnect();
		return parent::process_end ( $signal );
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'db_params' );
		$this->_get_param ( $params, 'write_mode' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'db_params' );
		$this->_set_param ( $params, 'write_mode' );

		return parent::set_params ( $params );
	}


	# ----- Overridable ----- #


	protected function db_record_array ( $data_element )
	{
		return $data_element;
	}


	# ----- Abstract ----- #


	abstract protected function db_object ( $db_params );

	abstract protected function insert_record ( $record_array );
	abstract protected function update_record ( $record_array );
	abstract protected function exists_record ( $record_array );


}
