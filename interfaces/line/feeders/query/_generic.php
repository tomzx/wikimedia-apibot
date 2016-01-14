<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Query-based feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Feeder_Query extends Feeder
{

	protected $query;


	# ----- Constructor ----- #

	function __construct ( $core, $start_params = array() )
	{
		$this->query = $this->query ( $core );
		parent::__construct ( $core, $start_params );
	}


	# ----- Tools ----- #

	public function queryname ()
	{
		return $this->query->queryname();
	}


	protected function query_feed_element ( $element, $element_key )
	{
		$signal = $this->data_signal ( $element, $this->data_type(), $element_key );
		return $this->feed_data_signal ( $signal );
	}


	# ----- Implemented ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Query";
	}


	protected function signal_log_job ()
	{
		$jobdesc = array ( 'params' => array() );
		$jobdesc['params']['query'] = $this->query->get_params();
		return $jobdesc;
	}


	protected function feed_data_signals ()
	{
		if ( ! $this->query->set_params ( $this->get_query_params() ) )
			return NULL;

		while ( true )
		{
			$element = $this->query->element ( true );
			if ( $element === false )
				return false;

			$result = $this->query_feed_element ( $element['value'], $element['key'] );
			if ( is_null ( $result ) )
				return false;
		}

		return true;
	}


	# ----- Setting params (query-wide only!) ----- #

	public function is_paramname_ok ( $name )
	{
		return $this->query->is_paramname_ok ( $name );
	}

	public function is_paramvalue_ok ( $name, $value )
	{
		return $this->query->is_paramvalue_ok ( $name, $value );
	}


	# ----- Overriding ----- #

	protected function get_params ()
	{
		return array_merge ( parent::get_params(), $this->get_query_params() );
	}

	protected function set_params ( $params )
	{
		return ( $this->set_query_params ( $params ) &&
			parent::set_params ( $params ) );
	}


	# ----- New ----- #

	protected function get_query_params ()
	{
		$params = array();

		$paramnames = $this->core->info->universal_query_paramnames();
		foreach ( $paramnames as $paramname )
			if ( isset ( $this->$paramname ) )
				$params[$paramname] = $this->$paramname;

		$paramnames = $this->core->info->param_querymodule_parameters_names (
			$this->queryname() );
		foreach ( $paramnames as $paramname )
			if ( isset ( $this->$paramname ) )
				$params[$paramname] = $this->$paramname;

		return $params;
	}

	protected function set_query_params ( $params )
	{
		$paramnames = $this->core->info->universal_query_paramnames();
		foreach ( $paramnames as $paramname )
			if ( isset ( $params[$paramname] ) )
				$this->$paramname = $params[$paramname];

		$paramnames = $this->core->info->param_querymodule_parameters_names (
			$this->queryname() );
		foreach ( $paramnames as $paramname )
			if ( isset ( $params[$paramname] ) )
				$this->$paramname = $params[$paramname];

		return true;
	}


	# ----- Abstract ----- #

	abstract protected function query ( &$core );


}
