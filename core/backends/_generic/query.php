<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backends: Generic: Query.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/backend.php' );


abstract class Backend_Query
{

	# --- Service objects --- #

	protected $backend;
	protected $action;   // an *_Action_Query module

	# --- Protected variables --- #

	protected $settings;

	# --- Data received --- #

	public $data;  // an array of elements


	# ----- Constructor ----- #

	function __construct ( $backend, $settings = array(), $default_params = array() )
	{
		$this->backend = $backend;

		$setparams_settings = $backend->settings->get ( "setparams" );

		$this->settings = $this->get_settings ( $settings, "settings" );

		$default_params = $this->get_settings ( $default_params, "defaults" );

		$this->action = $this->action ( $setparams_settings, $default_params );
	}


	# ----- Tools ----- #

	public function log ( $message, $loglevel = LL_INFO, $preface = NULL )
	{
		return $this->action->log ( $message, $loglevel, $preface );
	}


	public function is_operable ()
	{
		return $this->backend->is_operable();
	}


	protected function get_settings ( $settings, $type )
	{
		if ( ! is_array ( $settings ) )
			$settings = array();

		$module_settings = $this->backend->settings->get_withbackend (
			$this->backend_name(), "queries", $this->queryname(), $type );
		if ( is_array ( $module_settings ) )
			$settings = array_merge ( $module_settings, $settings );

		return $settings;
	}


	# ----- API access ----- #


	# --- Misc --- #


	protected function results ( $result )
	{
		if ( $result )
			if ( isset ( $this->action->data['query'] ) )
				if ( isset ( $this->action->data['query'][$this->querykey()] ) )
					return $this->action->data['query'][$this->querykey()];
				else
					return array();
			else
				return NULL;
		else
			return array();
	}


	# --- Without hooks (protected) --- #


	public function nohooks__is_paramname_ok ( $hook_object, $name )
	{
		return $this->action->is_paramname_ok ( $name );
	}

	public function nohooks__is_paramvalue_ok ( $hook_object, $name, $value, $setmode = NULL )
	{
		return $this->action->is_paramvalue_ok ( $name, $value, $setmode );
	}


	public function nohooks__get_params ( $hook_object )
	{
		return $this->action->get_params();
	}

	public function nohooks__set_params ( $hook_object, $params )
	{
		return $this->action->set_params ( $params );
	}


	public function nohooks__xfer ( $hook_object )
	{
		$result = $this->action->xfer();
		$this->data = $this->results ( $result );
		return $result;
	}

	public function nohooks__next ( $hook_object )
	{
		$result = $this->action->next();
		$this->data = $this->results ( $result );
		return $result;
	}


	# --- With hooks (entry points) --- #


	public function is_paramname_ok ( $name )
	{
		return $this->backend->hooks->call (
			get_class ( $this ) . '::is_paramname_ok',
			array ( $this, 'nohooks__is_paramname_ok' ),
			$this, $name
		);
	}

	public function is_paramvalue_ok ( $name, $value, $setmode = NULL )
	{
		return $this->backend->hooks->call (
			get_class ( $this ) . '::is_paramvalue_ok',
			array ( $this, 'nohooks__is_paramvalue_ok' ),
			$this, $name, $value, $setmode
		);
	}


	public function get_params ()
	{
		return $this->backend->hooks->call (
			get_class ( $this ) . '::get_params',
			array ( $this, 'nohooks__get_params' ),
			$this
		);
	}

	public function set_params ( $params )
	{
		return $this->backend->hooks->call (
			get_class ( $this ) . '::set_params',
			array ( $this, 'nohooks__set_params' ),
			$this, $params
		);
	}


	public function xfer ()
	{
		return $this->backend->hooks->call (
			get_class ( $this ) . '::xfer',
			array ( $this, 'nohooks__xfer' ),
			$this
		);
	}

	public function next ()
	{
		return $this->backend->hooks->call (
			get_class ( $this ) . '::next',
			array ( $this, 'nohooks__next' ),
			$this
		);
	}


	# ----- Abstract ----- #

	abstract protected function backend_name ();

	abstract protected function action ( $params, $settings );

	abstract protected function querykey ();

	abstract public function queryname ();

}
