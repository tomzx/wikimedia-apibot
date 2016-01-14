<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backend-independent Query: Generic.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


abstract class Query
{

	public $data;


	protected $core;
	protected $settings;

	protected $backend_query;


	# ----- Constructor ----- #

	function __construct ( $core, $default_params = array(), $settings = array() )
	{
		$this->core = $core;
		$this->settings = array_merge ( $this->default_settings(), $settings );

		$this->backend_query = $this->backend_query();

		$this->set_params ( $default_params );
	}


	# ----- Tools ----- #

	protected function log ( $message, $loglevel = LL_INFO, $preface = "" )
	{
		return $this->core->log->log ( $message, $loglevel, $preface );
	}


	protected function default_settings ()
	{
		$settings = $this->core->settings->merge (
			$this->core->settings->get ( 'queries' ),
			$this->query_family_name(), $this->query_name() );
		if ( empty ( $settings ) )
			$settings = array();

		if ( ! isset ( $settings['backends'] ) )
		{
			$settings['backends'] =
				$this->core->settings->get ( 'queries', 'backends' );

			if ( empty ( $settings['backends'] ) )
				$settings['backends'] = $this->supported_backends();

			if ( ! is_array ( $settings['backends'] ) )
				$settings['backends'] = array ( $settings['backends'] );
		}

		if ( ! isset ( $settings['return_objects'] ) )
			$settings['return_objects'] = false;

		return $settings;
	}


	protected function api_query ()
	{
		return NULL;
	}


	protected function web_query ()
	{
		return NULL;
	}


	protected function backend_query ()
	{
		foreach ( $this->settings['backends'] as $backend )

			switch ( $backend )
			{

				case "api" :
					$query = $this->api_query();
					if ( is_object ( $query ) && $query->is_operable() )
						break;

				case "web" :
					if ( is_object ( $query ) && $query->is_operable() )
						$query = $this->web_query();
					break;

				default :
					$this->log ( get_class ( $this ) . ": Unknown backend: $backend",
						LL_ERROR );
					$query = NULL;

			}

		return $query;
	}


	# ----- Parameters handling ----- #


	# --- Without hooks --- #


	public function nohooks__is_paramname_ok ( $hook_object, $paramname )
	{
		return $this->backend_query->is_paramname_ok ( $paramname );
	}


	public function nohooks__is_paramvalue_ok ( $hook_object, $paramname, $paramvalue )
	{
		return $this->backend_query->is_paramvalue_ok ( $paramname, $paramvalue );
	}


	public function nohooks__get_params ( $hook_object )
	{
		return $this->backend_query->get_params();
	}


	public function nohooks__set_params ( $hook_object, $params )
	{
		return $this->backend_query->set_params ( $params );
	}


	# --- With hooks --- #


	public function is_paramname_ok ( $paramname )
	{
		return $this->core->hooks->call (
			get_class ( $this ) . '::is_paramname_ok',
			array ( $this, 'nohooks__is_paramname_ok' ),
			$this, $paramname
		);
	}


	public function is_paramvalue_ok ( $paramname, $paramvalue )
	{
		return $this->core->hooks->call (
			get_class ( $this ) . '::is_paramvalue_ok',
			array ( $this, 'nohooks__is_paramvalue_ok' ),
			$this, $paramname, $paramvalue
		);
	}


	public function get_params ()
	{
		return $this->core->hooks->call (
			get_class ( $this ) . '::get_params',
			array ( $this, 'nohooks__get_params' ),
			$this
		);
	}


	public function set_params ( $params )
	{
		return $this->core->hooks->call (
			get_class ( $this ) . '::set_params',
			array ( $this, 'nohooks__set_params' ),
			$this, $params
		);
	}


	# ----- Externally accessible ----- #


	# --- Without hooks (protected!) --- #


	public function nohooks__xfer ( $hook_object )
	{
		$result = $this->backend_query->xfer();
		$this->data = &$this->backend_query->data;
		return $result;
	}


	public function nohooks__next ( $hook_object )
	{
		$result = $this->backend_query->next();
		$this->data = &$this->backend_query->data;
		return $result;
	}


	public function nohooks__element ( $hook_object, $with_key = false )
	{
		if ( ! isset ( $this->data ) )
		{
			$result = $this->xfer();
			if ( ( $result === false ) || is_null ( $result ) )
			{
				unset ( $this->data );
				return $result;
			}
		}

		$member = each ( $this->data );
		if ( $member === false )
		{
			$result = $this->next();
			if ( ( $result === false ) || is_null ( $result ) )
			{
				unset ( $this->data );
				return $result;
			}
			else
			{
				$member = each ( $this->data );
			}
		}

		if ( ! is_array ( $member ) )
			return NULL;

		if ( $with_key )
			$result = array ( 'key' => $member['key'], 'value' => $member['value'] );
		else
			$result = $member['value'];

		return $this->postprocess_result ( $result );
	}


	# --- Full access, with hooks --- #


	public function queryname ()
	{
		return $this->backend_query->queryname();
	}


	public function xfer ()
	{
		return $this->core->hooks->call (
			get_class ( $this ) . '::xfer',
			array ( $this, 'nohooks__xfer' ),
			$this
		);
	}


	public function next ()
	{
		return $this->core->hooks->call (
			get_class ( $this ) . '::next',
			array ( $this, 'nohooks__next' ),
			$this
		);
	}


	public function element ( $with_key = false )
	{
		return $this->core->hooks->call (
			get_class ( $this ) . '::element',
			array ( $this, 'nohooks__element' ),
			$this, $with_key
		);
	}


	# ----- Overridable ----- #


	protected function postprocess_result ( $result )
	{
		return $result;
	}


	# ----- Abstract ----- #

	abstract protected function query_family_name ();

	abstract protected function query_name ();

	abstract protected function supported_backends();


}
