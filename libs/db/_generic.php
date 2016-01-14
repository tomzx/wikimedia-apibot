<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Database: Generic database class (simplest functions only).
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


abstract class Database
{

	protected $db_params;

	protected $db;


	protected $core;


	# ----- Constructor ----- #


	function __construct ( $core )
	{
		$this->core = $core;
	}


	# ----- Tools ----- #


	protected function log ( $message, $loglevel = LL_INFO, $preface = "" )
	{
		return $this->core->log->log ( $message, $loglevel, $preface );
	}


	# ----- Abstract ----- #


	# --- General --- #


	abstract public function connect ( $db_params = NULL );
	abstract public function disconnect ();

	abstract public function query ( $SQL );

	abstract public function sanitize ( $string );


	# --- Info --- #


	abstract public function tables ();
	abstract public function columns ( $table );


	# --- Data definition --- #

	# Data values will be auto-sanitized, but all other info will NOT be!!!

	abstract public function create_table ( $table, $columns, $indexes );
	abstract public function drop_table ( $table );
	abstract public function rename_table ( $old_name, $new_name );
	abstract public function truncate_table ( $table );

	abstract public function add_columns ( $table, $columns ); // full col descriptions
	abstract public function delete_columns ( $table, $columns ); // col names only
	abstract public function modify_columns ( $table, $columns ); // full col descriptions

	abstract public function add_indexes ( $table, $indexes );  // full index descriptions
	abstract public function delete_indexes ( $table, $indexes ); // index names only


	# --- Data manipulation --- #

	# Data values will be auto-sanitized, but names and conditions will not be.

	abstract public function select ( $table, $fields, $conditions = NULL,
		$order_by = NULL, $limit = NULL, $offset = NULL );

	abstract public function insert ( $table, $record_array );

	abstract public function update ( $table, $record_array, $conditions = NULL,
		$order_by = NULL, $limit = NULL );

	abstract public function delete ( $table, $conditions, $order_by = NULL,
		$limit = NULL );


}
