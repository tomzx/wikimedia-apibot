<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - MySQL SQL-string database feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_mysql.php' );


class Feeder_DB_MySQL_SQL extends Feeder_DB_MySQL
{

	public $SQL;  // the SQL command string that will select the records to feed


	# ----- Overriding -----#


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".SQL";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'SQL' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'SQL' );

		return parent::set_params ( $params );
	}


	# ----- Implemented ----- #


	# --- From Line_Slot class --- #


	protected function signal_log_job ()
	{
		$jobdesc = array ( 'params' => array() );
		$jobdesc['params']['SQL'] = $this->SQL;
		return $jobdesc;
	}


	# --- From Feeder_DB --- #


	protected function query_portion ( $limit, $offset )
	{
		if ( empty ( $this->SQL ) )
		{
			$this->log ( "The SQL query string is not set!", LL_PANIC );
			die();
		}

		if ( strrpos ( $this->SQL, ";" ) == ( strlen ( $this->SQL ) - 1 ) )
			$this->SQL = substr ( $this->SQL, 0, strlen ( $this->SQL ) - 1 );

		if ( ! is_null ( $limit ) )
			$this->SQL .= " LIMIT " . $limit;

		if ( ! is_null ( $offset ) )
			$this->SQL .= " OFFSET " . $offset;

		return $this->db->query ( $this->SQL );
	}


}
