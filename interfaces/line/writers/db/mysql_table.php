<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - MySQL table writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_mysql.php' );


abstract class Writer_MySQL_Table extends Writer_MySQL
{

	public $table;

	public $fields;

	public $match_fields = array();

	public $order_by;

	public $limit;


	# ----- Tools ----- #


	protected function conditions_sets ( $record_array )
	{
		$conditions_sets = array();

		foreach ( $record_array as $field => $value )
			if ( in_array ( $field, $this->match_fields ) )
				$conditions_sets[] = "( `" . $field . "` = '" . $value . "' )";

		return implode ( " AND ", $conditions_sets );
	}


	# ----- Implemented ----- #


	protected function insert_record ( $record_array )
	{
		foreach ( $record_array as $field => $value )
			if ( isset ( $this->fields[$field] ) )
			{
				unset ( $record_array[$field] );
				$record_array[$this->fields[$field]] = $value;
			}

		return $this->db->insert ( $this->table, $record_array );
	}


	protected function update_record ( $record_array )
	{
		foreach ( $record_array as $field => $value )
			if ( isset ( $this->fields[$field] ) )
			{
				unset ( $record_array[$field] );
				$record_array[$this->fields[$field]] = $value;
			}

		$conditions = $this->conditions_sets ( $record_array );

		return $this->db->update ( $this->table, $record_array, $conditions,
			$this->order_by, $this->limit );
	}


	protected function exists_record ( $record_array )
	{
		$fields = array ( "COUNT(*)" );

		$conditions = $this->conditions_sets ( $record_array );

		$result = $this->db->select ( $this->table, $fields, $conditions,
			$this->order_by, 1 );

		if ( is_bool ( $result ) )
			return $result;

		return ( count ( $result ) > 0 );
	}


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Table";
	}


	protected function signal_log_job ()
	{
		$jobdesc = array ( 'params' => array() );
		$jobdesc['params']['table' ] = $this->table;
		$jobdesc['params']['fields'] = $this->fields;
		$jobdesc['params']['match_fields'] = $this->match_fields;
		$jobdesc['params']['order_by'] = $this->order_by;
		$jobdesc['params']['limit' ] = $this->limit;
		return $jobdesc;
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'table' );
		$this->_get_param ( $params, 'fields' );
		$this->_get_param ( $params, 'match_fields' );
		$this->_get_param ( $params, 'order_by' );
		$this->_get_param ( $params, 'limit' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'table' );
		$this->_set_param ( $params, 'fields' );
		$this->_set_param ( $params, 'match_fields' );
		$this->_set_param ( $params, 'order_by' );
		$this->_set_param ( $params, 'limit' );

		return parent::set_params ( $params );
	}


}
