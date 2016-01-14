<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Table-specifying MySQL database feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_mysql.php' );


class Feeder_DB_MySQL_Table extends Feeder_DB_MySQL
{

	public $table;   // name of the table to export (WILL NOT BE SANITIZED!)

	public $fields;  // an array with the fields to export (WILL NOT BE SANITIZED!)

	public $conditions;  // a string with conditions that limit the selection scope


	# ----- Overriding ----- #


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'table' );
		$this->_get_param ( $params, 'fields' );
		$this->_get_param ( $params, 'conditions' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'table' );
		$this->_set_param ( $params, 'fields' );
		$this->_set_param ( $params, 'conditions' );

		return parent::set_params ( $params );
	}


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Table";
	}


	protected function signal_log_job ()
	{
		$jobdesc = parent::signal_log_job();
		$jobdesc['params']['table'] = $this->table;
		$jobdesc['params']['fields'] = $this->fields;
		$jobdesc['params']['conditions'] = $this->conditions;
		return $jobdesc;
	}


	# ----- Implemented ----- #


	protected function query_portion ( $limit, $offset )
	{
		return $this->db->select ( $this->table, $this->fields,
			$this->conditions, $this->records_order_by, $limit, $offset );
	}


}
