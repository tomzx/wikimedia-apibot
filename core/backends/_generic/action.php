<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backends: Generic: Action.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/backend.php' );


abstract class Action
{

	# --- Service objects --- #

	protected $backend;
	protected $module;   // an API_Module_*

	# --- Protected variables --- #

	protected $settings;

	# --- Public variables --- #

	public $setnames = array();  // convert paramnames to these

	public $data;  // the data returned by the module after call


	# ----- Constructor ----- #

	function __construct ( $backend, $settings = array(), $defaults = array() )
	{
		$this->backend = $backend;

		$setparams_settings = $backend->settings->get ( "setparams" );
		$this->module = $this->module ( $setparams_settings );

		$this->settings = $this->get_settings ( $settings, "settings" );

		$defaults = $this->get_settings ( $defaults, "defaults" );
		$this->load_default_params ( $defaults );
	}


	# ----- Tools ----- #

	public function log ( $message, $loglevel = LL_INFO, $preface = "Action: " )
	{
		return $this->backend->log ( $message, $loglevel, $preface );
	}


	protected function get_settings ( $settings, $type )
	{
		if ( ! is_array ( $settings ) )
			$settings = array();

		$module_settings = $this->backend->settings->get_withbackend (
			$this->backend_name(), "actions", $this->modulename(), $type );
		if ( is_array ( $module_settings ) )
			$settings = array_merge ( $module_settings, $settings );

		return $settings;
	}


	protected function module_paramnames ()
	{
		return $this->backend->info->module_paramnames ( $this->modulename() );
	}


	protected function add_default_params ( $params, $paramnames )
	{
		foreach ( $paramnames as $paramname )
			if ( isset ( $this->$paramname ) && ! isset ( $params[$paramname] ) )
				$params[$paramname] = $this->$paramname;

		return $params;
	}


	protected function load_default_params ( $params )
	{
		if ( ! is_array ( $params ) )
		{
			$this->log ( get_class ( $this ) .
				": Default params given must be an array!", LL_ERROR );
			return false;
		}

		foreach ( $params as $name => $value )
			if ( is_null ( $value ) )
				unset ( $this->$name );
			else
				$this->$name = $value;

		return true;
	}


	protected function translate_params ( $params, $setnames )
	{
		$translated = array();

		foreach ( $params as $name => $value )
		{
			if ( substr ( $name, 0, 1 ) == "_" )  // Apibot internal use paramnames
			{
				$translated[$name] = $value;
			}

			else
			{
				if ( isset ( $setnames[$name] ) )
					$name = $setnames[$name];

				if ( is_array ( $name ) )
				{

					if ( is_bool ( $value ) || is_null ( $value ) )
						$translated[( $value ? $name['true'] : $name['false'] )] = $value;

					else
						$this->log ( "Could not correctly translate a parameter!",
							LL_WARNING );
				}
				else
				{
					$translated[$name] = $value;
				}

			}

		}

		return $translated;
	}


	protected function set_token ( &$params, $token )
	{
		if ( ! isset ( $params['token'] ) )
			$params['token'] = $token;
	}


	public function modulename ()
	{
		return $this->module->modulename();
	}


	public function mustbeposted ()
	{
		return $this->module->mustbeposted();
	}


	# ----- Data parsing ----- #

	public function data_areas_keys ()
	{
		return $this->module->data_areas_keys();
	}

	public function data_area ( $key )
	{
		return $this->module->data_area ( $key );
	}

	public function results ()
	{
		return $this->module->results();
	}

	public function errors ()
	{
		return $this->module->errors();
	}

	public function warnings ()
	{
		return $this->module->warnings();
	}

	public function limits ()
	{
		return $this->module->limits();
	}


	# --- Splicing results --- #

	public function data_area_elements_count ( $area_key )
	{
		return $this->module->data_area_elements_count ( $area_key );
	}

	public function data_area_elements_keys ( $area_key )
	{
		return $this->module->data_area_elements_keys ( $area_key );
	}

	public function data_area_element ( $area_key, $element_key )
	{
		return $this->module->data_area_element ( $area_key, $element_key );
	}


	public function data_area_first_element ( $area_key )
	{
		return $this->module->data_area_first_element ( $area_key );
	}

	public function data_area_next_element ( $area_key )
	{
		return $this->module->data_area_next_element ( $area_key );
	}

	public function data_area_last_element ( $area_key )
	{
		return $this->module->data_area_last_element ( $area_key );
	}


	public function results_elements_count ()
	{
		return $this->module->results_elements_count();
	}

	public function results_elements_keys ()
	{
		return $this->module->results_elements_keys();
	}

	public function results_element ( $key )
	{
		return $this->module->results_element ( $key );
	}


	# ----- Getting / setting parameters ----- #

	# --- Separate parameters access --- #

	public function is_paramname_ok ( $name )
	{
		return $this->module->is_paramname_ok ( $name );
	}

	public function is_param_under_limit ( $name )
	{
		return $this->module->is_param_under_limit ( $name );
	}

	public function is_paramvalue_ok ( $name, $value, $setmode = NULL )
	{
		return $this->module->is_paramvalue_ok ( $name, $value, $setmode );
	}

	public function param_isset ( $name )
	{
		return $this->module->param_isset ( $name );
	}

	public function get_param ( $name )
	{
		return $this->module->get_param ( $name );
	}

	public function set_param ( $name, $value = "" )
	{
		return $this->module->set_param ( $name, $value );
	}

	public function clear_param ( $name, $value = NULL )
	{
		return $this->module->clear_param ( $name, $value );
	}


	public function get_file ( $name )
	{
		return $this->module->get_file ( $name );
	}

	public function set_file ( $name, $filename )
	{
		return $this->module->set_file ( $name, $filename );
	}

	public function clear_file ( $name )
	{
		return $this->module->clear_file ( $name );
	}


	# --- Parameters arrays access --- #


	public function nohooks__get_params ( $hook_object )
	{
		return $this->module->get_params();
	}

	public function nohooks__set_params ( $hook_object, $params )
	{
		$paramnames = $this->module_paramnames();
		$params = $this->add_default_params ( $params, $paramnames );

		$translated = $this->translate_params ( $params, $this->setnames );

		return $this->module->set_params ( $translated );
	}


	public function nohooks__clear_params ( $hook_object )
	{
		return $this->module->clear_params();
	}


	public function nohooks__get_files ( $hook_object )
	{
		return $this->module->get_files();
	}

	public function nohooks__set_files ( $hook_object, $files )
	{
		return $this->module->set_files ( $files );
	}

	public function nohooks__clear_files ( $hook_object )
	{
		return $this->module->clear_files();
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


	public function clear_params ()
	{
		return $this->backend->hooks->call (
			get_class ( $this ) . '::clear_params',
			array ( $this, 'nohooks__clear_params' ),
			$this
		);
	}


	public function get_files ()
	{
		return $this->backend->hooks->call (
			get_class ( $this ) . '::get_files',
			array ( $this, 'nohooks__get_files' ),
			$this
		);
	}


	public function set_files ( $files )
	{
		return $this->backend->hooks->call (
			get_class ( $this ) . '::set_files',
			array ( $this, 'nohooks__set_files' ),
			$this, $params
		);
	}


	public function clear_files ()
	{
		return $this->backend->hooks->call (
			get_class ( $this ) . '::clear_files',
			array ( $this, 'nohooks__clear_files' ),
			$this
		);
	}


	# ----- API access ----- #


	public function nohooks__xfer ( $hook_object )
	{
		$result = $this->module->xfer();
		$this->data = &$this->module->data;
		return $result;
	}


	public function xfer ()
	{
		return $this->backend->hooks->call (
			get_class ( $this ) . '::xfer',
			array ( $this, 'nohooks__xfer' ),
			$this
		);
	}


	# ----- Abstract ----- #


	abstract protected function module ( $settings );
	abstract protected function backend_name ();


}
