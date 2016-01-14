<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Database: MySQL database class (simplest functions only).
#
#  ATTENTION !!!
#
#  All parameters (except data values) to the class methods are NOT sanitized!
#  Always sanitize them (using the sanitize() method) before passing to a method,
#  or you risk SQL injection and/or other nasty attacks!
#
#  You have been warned!
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class MySQL extends Database
{


	# ----- Tools ----- #


	protected function column_definition_sql ( $column, $table = "" )
	{
		if ( ! isset ( $column['name'] ) )
		{
			$this->log ( "Creating table " . $table .
				": A column is not given a name!", LL_PANIC );
			die();
		}
		if ( ! isset ( $column['type'] ) )
		{
			$this->log ( "Creating table " . $table .
				": Column `" . $column['name'] . "` is not given a type!", LL_PANIC );
			die();
		}

		$SQL = $column['name'] . " " . $column['type'];
		if ( isset ( $column['values'] ) )
			$SQL .= " (" . implode ( ",", $column['values'] ) . " )";

		if ( isset ( $column['length'] ) )
			$SQL .= " " . $column['length'];
		if ( isset ( $column['decimals'] ) )
			$SQL .= " " . $column['decimals'];
		if ( isset ( $column['unsigned'] ) && $column['unsigned'] )
			$SQL .= " UNSIGNED";
		if ( isset ( $column['zerofill'] ) && $column['zerofill'] )
			$SQL .= " ZEROFILL";

		if ( isset ( $column['charset'] ) )
			$SQL .= " CHARACTER SET " . $column['charset'];
		if ( isset ( $column['collation'] ) )
			$SQL .= " COLLATE " . $column['collation'];

		if ( isset ( $column['default'] ) )
			$SQL .= " DEFAULT " . $column['default'];

		if ( isset ( $column['autoinc'] ) && $column['autoinc'] )
			$SQL .= " AUTO_INCREMENT";

		return $SQL;
	}


	protected function index_definition_sql ( $index, $table )
	{
		if ( ! isset ( $index['name'] ) )
		{
			$this->log ( "Creating table: An index is not given a name!", LL_PANIC );
			die();
		}
		if ( ! isset ( $index['type'] ) )
		{
			$this->log ( "Creating table: Index `" . $index['name'] .
				"` is not given a type!", LL_PANIC );
			die();
		}
		if ( ! isset ( $index['columns'] ) )
		{
			$this->log ( "Creating table: Index `" . $index['name'] .
				"` is not given columns to index!", LL_PANIC );
			die();
		}

		$SQL = " INDEX " . $index['name'] . " " . $index['type'];
		if ( is_array ( $index['columns'] ) )
			$index['columns'] = implode ( ",", $index['columns'] );
		$SQL .= " (" . $index['columns'] . ")";

		return $SQL;
	}


	protected function conditions_orderby_limit_sql ( $conditions = NULL,
		$order_by = NULL, $limit = NULL, $offset = NULL )
	{
		$SQL = "";

		if ( ! empty ( $conditions ) )
			$SQL .= " WHERE ( " . $conditions . " )";

		if ( ! is_null ( $order_by ) )
			$SQL .= " ORDER_BY " . $order_by;

		if ( ! is_null ( $limit ) )
			$SQL .= " LIMIT " . $limit;

		if ( ! is_null ( $offset ) )
			$SQL .= " OFFSET " . $offset;

		return $SQL;
	}


	# ----- Implemented ----- #


	# --- General --- #


	public function connect ( $db_params = NULL )
	{
		if ( ! isset ( $db_params['host'] ) )
		{
			$this->log ( "Database host not set!", LL_PANIC );
			die();
		}

		if ( ! isset ( $db_params['name'] ) )
		{
			$this->log ( "Database name not set!", LL_PANIC );
			die();
		}

		if ( ! isset ( $db_params['user'] ) )
		{
			$this->log ( "Database user not set!", LL_PANIC );
			die();
		}

		if ( ! isset ( $db_params['pass'] ) )
		{
			$this->log ( "Database password not set!", LL_PANIC );
			die();
		}

		$port = ( isset ( $db_params['port'] )
			? $db_params['port']
			: "3306" );

		if ( isset ( $db_params['socket'] ) )
			$this->db = mysqli_connect ( $db_params['host'], $db_params['user'],
				$db_params['pass'], $db_params['name'], $port, $db_params['socket'] );
		else
			$this->db = mysqli_connect ( $db_params['host'], $db_params['user'],
				$db_params['pass'], $db_params['name'], $port );

		if ( is_null ( $this->db ) )
		{
			$this->log ( "Could not connect to database " .
				$db_params['name'] . "!", LL_PANIC );
			die();
		}

		$charset = ( isset ( $db_params['charset'] )
			? $db_params['charset']
			: "UTF-8" );

		$this->query ( "SET CHARACTER SET " . $charset );
		$this->query ( "SET NAMES " . $charset );
	}


	public function disconnect ()
	{
		if ( mysqli_close ( $this->db ) )
			$this->db = NULL;
		else
			$this->log ( "Could not close the database!", LL_ERROR );
	}


	public function query ( $SQL )
	{
		$result = mysqli_query ( $this->db, $query );

		if ( is_bool ( $result ) )
			return $result;

		$record_array = mysqli_fetch_all ( $result, MYSQLI_ASSOC );
		mysqli_free_result ( $result );

		return $record_array;
	}


	public function sanitize ( $string )
	{
		return mysqli_real_escape_string ( $string );
	}


	# --- Info --- #


	public function tables ()
	{
		return $this->query ( "SHOW TABLES" );
	}


	public function columns ( $table )
	{
		return $this->query ( "SHOW COLUMNS FROM " . $table );
	}


	# --- Data definition --- #


	public function create_table ( $table, $columns, $indexes = array() )
	{
		$column_SQLs = array();
		foreach ( $columns as $column )
			$column_SQLs[] = $this->column_definition_sql ( $column, $table );

		$index_SQLs = array();
		foreach ( $indexes as $index )
			$index_SQLs[] = $this->index_definition_sql ( $index, $table );

		$SQL = "CREATE TABLE " . $table .
			" (" . implode ( ", ", $column_SQLs ) . " )" .
			implode ( ", ", $index_SQLs );

		return $this->query ( $SQL );
	}


	public function drop_table ( $table )
	{
		$SQL = "DROP TABLE " . $table;
		return $this->query ( $SQL );
	}


	public function rename_table ( $old_name, $new_name )
	{
		$SQL = "RENAME TABLE " . $old_name . " TO " . $new_name;
		return $this->query ( $SQL );
	}


	public function truncate_table ( $table )
	{
		$SQL = "TRUNCATE TABLE " . $table;
		return $this->query ( $SQL );
	}


	public function add_columns ( $table, $columns )
	{
		$column_SQLs = array();
		foreach ( $columns as $column )
			$column_SQLs[] = " ADD " . $this->column_definition_sql ( $column, $table );

		$SQL = "ALTER TABLE " . $table . implode ( ", ", $column_SQLs );
		return $this->query ( $SQL );
	}


	public function delete_columns ( $table, $columns )
	{
		$column_SQLs = array();
		foreach ( $columns as $column )
			$column_SQLs[] = " DROP " . $column;

		$SQL = "ALTER TABLE " . $table . implode ( ", ", $column_SQLs );
		return $this->query ( $SQL );
	}


	public function modify_columns ( $table, $columns )
	{
		$column_SQLs = array();
		foreach ( $columns as $column )
			$column_SQLs[] = " MODIFY " . $this->column_definition_sql ( $column, $table );

		$SQL = "ALTER TABLE " . $table . implode ( ", ", $column_SQLs );
		return $this->query ( $SQL );
	}


	public function add_indexes ( $table, $indexes )
	{
		$index_SQLs = array();
		foreach ( $indexes as $index )
			$index_SQLs[] = " ADD " . $this->index_definition_sql ( $index, $table );

		$SQL = "ALTER TABLE " . $table . implode ( ", ", $index_SQLs );
		return $this->query ( $SQL );
	}


	public function delete_indexes ( $table, $indexes )
	{
		$index_SQLs = array();
		foreach ( $indexes as $index )
			$index_SQLs[] = " DROP " . $index;

		$SQL = "ALTER TABLE " . $table . implode ( ", ", $index_SQLs );
		return $this->query ( $SQL );
	}


	# --- Data manipulation --- #


	public function select ( $table, $fields, $conditions = NULL,
		$order_by = NULL, $limit = NULL, $offset = NULL )
	{
		$SQL = "SELECT `" . implode ( "`, `", $fields ) . "` FROM `" . $table . "`" .
			$this->conditions_orderby_limit_sql ( $conditions, $order_by, $limit,
				$offset );
		return $this->query ( $SQL );
	}


	public function insert ( $table, $record_array )
	{
		$fields_SQLs = array();
		$values_SQLs = array();
		foreach ( $record_array as $field => $value )
		{
			$fields_SQLs[] = "`" . $field . "`";
			$values_SQLs[] = "'" . $this->sanitize ( $value ) . "'";
		}

		$SQL = "INSERT INTO `" . $table . "` " . implode ( ", ", $fields_SQLs ) .
			" VALUES " . implode ( ", ", $values_SQLs );
		return $this->query ( $SQL );
	}


	public function update ( $table, $record_array, $conditions = NULL,
		$order_by = NULL, $limit = NULL )
	{
		$field_SQLs = array();
		foreach ( $record_array as $field => $value )
			$field_SQLs[] = "`" . $field . "` = '" . $this->sanitize ( $value ) . "'";

		$SQL = "UPDATE `" . $table . "` SET " . implode ( ", ", $field_SQLs ) .
			$this->conditions_orderby_limit_sql ( $conditions, $order_by, $limit );
		return $this->query ( $SQL );
	}


	public function delete ( $table, $conditions, $order_by = NULL, $limit = NULL )
	{
		$SQL = "DELETE FROM `" . $table . "`" .
			$this->conditions_orderby_limit_sql ( $conditions, $order_by, $limit );
		return $this->query ( $SQL );
	}


}
