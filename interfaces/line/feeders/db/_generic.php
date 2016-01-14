<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic database feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );


abstract class Feeder_DB extends Feeder
{

	protected $db;


	public $db_params;

	public $records_order_by;    // SQL order_by string
	public $records_limit;       // how many records should be fed
	public $records_offset = 0;  // at what queried record the feeding should start
	public $portion_limit = 100; // size of the query fetch portions


	# ----- Constructor ----- #


	function __construct ( $core, $start_params = array() )
	{
		parent::__construct ( $core, $start_params );

		$this->db = $this->db_object ( $core );

		if ( isset ( $start_params['db'] ) )
			$this->db_params = $start_params['db'];
	}


	# ----- Overriding ----- #


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'db_params' );
		$this->_get_param ( $params, 'records_order_by' );
		$this->_get_param ( $params, 'records_limit' );
		$this->_get_param ( $params, 'records_offset' );
		$this->_get_param ( $params, 'portion_limit' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'db_params' );
		$this->_set_param ( $params, 'records_order_by' );
		$this->_set_param ( $params, 'records_limit' );
		$this->_set_param ( $params, 'records_offset' );
		$this->_set_param ( $params, 'portion_limit' );

		return parent::set_params ( $params );
	}


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".DB";
	}


	protected function signal_log_job ()
	{
		$jobdesc = parent::signal_log_job();
		$jobdesc['params']['records_order_by'] = $this->records_order_by;
		$jobdesc['params']['records_limit'   ] = $this->records_limit;
		$jobdesc['params']['records_offset'  ] = $this->records_offset;
		return $jobdesc;
	}


	protected function data_type ()
	{
		return "array/*";
	}


	protected function send_feed ()
	{
		$this->db->connect ( $this->db_params );

		$result = parent::send_feed();

		$this->db->disconnect ();

		return $result;
	}


	# ----- Implemented ----- #


	protected function feed_data_signals ()
	{
		$counter = 0;

		while ( ! isset ( $this->records_limit ) || ( $counter < $this->records_limit ) )
		{
			$records = $this->db->query_portion ( $this->portion_limit,
				$this->records_offset + $counter );

			if ( empty ( $records ) )
				break;

			foreach ( $records as $record )
			{
				$signal = $this->data_element ( $record, $this->data_type() );
				$result = $this->feed_data_signal ( $signal );
				if ( is_null ( $result ) )
					return false;
				$counter++;
			}

		}

	}


	# ----- Abstract ----- #


	// data_type() is inherited as abstract

	abstract protected function db_object ( $core );

	abstract protected function query_portion ( $limit, $offset );


}
