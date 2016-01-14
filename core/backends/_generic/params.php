<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backends: Generic: Params.
#
#  Will utilize the Info module or pre-set standard settings, if set.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/info.php' );


abstract class Params
{

	# --- Internal --- #

	protected $hooks;
	protected $info;

	protected $paramdesc;

	protected $params;
	protected $files;

	protected $saved_params;


	# --- Public --- #

	public $settings;


	# ----- Constructor ----- #

	function __construct ( $hooks, $info = NULL, $settings = array() )
	{
		$this->hooks = $hooks;

		$this->settings = $settings;
		if ( ! isset ( $this->settings['lax_mode'] ) )
			$this->settings['lax_mode'] = false;

		if ( ! is_null ( $info ) )
			$this->info = $info;

		$this->paramdesc = $this->paramdesc();
		if ( is_null ( $this->paramdesc ) )
			throw new ApibotException_InternalError (
				"Class " . get_class ( $this ) . " could not find confirmation" .
				" that these params are supported by the wiki" );

		$this->clear_params();
		$this->clear_files();
	}


	# ----- Internal ----- #

	protected function log ( $msg, $loglevel = LL_INFO, $preface = "params: " )
	{
		if ( isset ( $this->info ) )
			return $this->info->log ( $msg, $loglevel, $preface );
	}


	# ----- Parameters servicing ----- #

	# --- General --- #

	public function mustbeposted ()
	{
		return isset ( $this->paramdesc['mustbeposted'] );
	}

	public function limit_max ()
	{
		return $this->param_max ( 'limit' );
	}


	# --- Obtaining params info --- #

	public function param_type ( $paramname )
	{
		if ( isset ( $this->paramdesc['params'][$paramname]['type'] ) )
			return $this->paramdesc['params'][$paramname]['type'];
		return NULL;
	}


	private function param_element_isset ( $paramname, $elementname )
	{
		if ( isset ( $this->paramdesc['params'][$paramname] ) )
			return isset ( $this->paramdesc['params'][$paramname][$elementname] );
		return NULL;
	}

	public function param_multi ( $paramname )
	{
		return $this->param_element_isset ( $paramname, 'multi' );
	}

	public function param_required ( $paramname )
	{
		return $this->param_element_isset ( $paramname, 'required' );
	}

	public function param_deprecated ( $paramname )
	{
		return $this->param_element_isset ( $paramname, 'deprecated' );
	}

	public function param_allows_duplicates ( $paramname )
	{
		return $this->param_element_isset ( $paramname, 'allows_duplicates' );
	}


	public function param_default ( $paramname )
	{
		if ( isset ( $this->paramdesc['params'][$paramname]['default'] ) )
			return $this->paramdesc['params'][$paramname]['default'];
		return NULL;
	}


	public function param_limit ( $paramname )
	{
		if ( isset ( $this->paramdesc['params'][$paramname]['limit'] ) )
			return $this->paramdesc['params'][$paramname]['limit'];
		return NULL;
	}


	public function param_max ( $paramname )
	{
		if ( isset ( $this->paramdesc['params'][$paramname]['max'] ) )
			return $this->paramdesc['params'][$paramname]['max'];
		return NULL;
	}


	# --- Checking parameter data --- #

	public function is_paramname_ok ( $name )
	{
		if ( $this->settings['lax_mode'] )
			return true;
		if ( ! isset ( $this->paramdesc ) )
			return NULL;
		return isset ( $this->paramdesc['params'][$name] );
	}

	public function is_param_under_limit ( $name )
	{
		if ( $this->param_multi ( $name ) && isset ( $this->params[$name] ) &&
			( count ( $this->params[$name] ) >= $this->param_limit ( $name ) ) )
			return false;
		return true;
	}

	public function is_paramvalue_ok ( $name, $value, $lax_mode = NULL )
	{
		if ( is_null ( $lax_mode ) )
			$lax_mode = $this->settings['lax_mode'];

		if ( $lax_mode )
			return true;

		if ( ! isset ( $this->paramdesc['params'][$name] ) )
			return false;

		if ( ! $this->is_param_under_limit ( $name ) )
			return false;

		$type = $this->param_type ( $name );

		if ( is_array ( $type ) )
			return in_array ( $value, $type );

		if ( ( $type == "boolean" ) || ( $type == "bool" ) )
			return is_bool ( $value );

		if ( $type == "integer" )
			return is_numeric ( $value );

		if ( $type == "limit" )
			return ( ( $value == "max" ) ||
			         ( is_numeric ( $value ) &&
			           ( $value > 0 ) &&
			           ( $value <= $this->param_max ( $name ) )
			         )
			       );

		if ( $type == "string" )
			return ( is_string ( $value ) || is_numeric ( $value ) );  // both are OK

		if ( $type == "user" )
			return ( is_string ( $value ) || is_numeric ( $value ) );

		if ( $type == "timestamp" )
			return preg_match ( '/^\d\d\d\d-\d\d-\d\d\D\d\d:\d\d:\d\d\D?$/u', $value );

		if ( $type == "namespace" )
		{
			if ( ! isset ( $this->info ) )
				return true;  // will just have to trust what is passed

			$namespaces_ids = $this->info->namespaces_ids();
			$namespaces_allnames = $this->info->namespaces_allnames();
			if ( is_array ( $namespaces_ids ) && is_array ( $namespaces_allnames ) )
			{
				$namespaces = array_merge ( $namespaces_ids, $namespaces_allnames );
				return in_array ( $value, $namespaces );
			}
			else
			{
				return NULL;
			}
		}

		throw new ApibotException_InternalError (
			"Unknown parameter type in Params->is_paramvalue_ok()" );
	}


	# --- Getting / Setting params / files --- #

	# - Setting individual params / files - #

	public function param_isset ( $name )
	{
		return isset ( $this->params[$name] );
	}

	public function get_param ( $name )
	{
		if ( isset ( $this->params[$name] ) )
			return $this->params[$name];
		else
			return NULL;
	}

	public function set_param ( $name, $value )
	{
		if ( is_null ( $value ) )
		{
			unset ( $this->params[$name] );
			return true;
		}

		if ( is_array ( $value ) )
		{
			if ( $this->param_multi ( $name ) )
			{
				foreach ( $value as $element )
					if ( ! $this->set_param ( $name, $element ) )
						return false;
				return true;
			}
			else
			{
				return false;
			}
		}

		if ( ! $this->is_paramvalue_ok ( $name, $value ) )
			return false;

		if ( $this->param_deprecated ( $name ) )
			$this->log ( "Parameter " . $name . " is deprecated here", LL_DEBUG );

		if ( $this->param_type ( $name ) == "boolean" )
			if ( $value )
				$this->params[$name] = "";
			else
				unset ( $this->params[$name] );

		if ( $this->param_multi ( $name ) )
		{
			if ( ! isset ( $this->params[$name] ) )
				$this->params[$name] = array();
			$this->params[$name][] = $value;
		}
		else
		{
			$this->params[$name] = $value;
		}

		return true;
	}

	public function clear_param ( $name, $value = NULL )
	{
		if ( ! is_array ( $this->params ) )
			return true;
		if ( is_null ( $value ) || (
			! is_array ( $this->params[$name] ) && $this->params[$name] === $value ) )
		{
			unset ( $this->params[$name] );
		}
		elseif ( is_array ( $this->params[$name] ) )
		{
			$keys = array_keys ( $this->params[$name], $value );
			foreach ( $keys as $key )
				unset ( $this->params[$name][$key] );
		}
		return true;
	}


	public function set_bool_params ( $name_true, $name_false, $value )
	{
		if ( $value === true )
			$this->set_param ( $name_true, "" );
		if ( $value === false )
			$this->set_params ( $name_false, "" );
	}


	public function get_file ( $name )
	{
		if ( isset ( $this->files[$name] ) )
			return $this->files[$name];
		else
			return NULL;
	}

	public function set_file ( $name, $filename )
	{
		if ( is_readable ( $filename ) )
		{
			$this->files[$name] = $filename;
			return true;
		}
		else
		{
			return false;
		}
	}

	public function clear_file ( $name )
	{
		if ( ! is_array ( $this->files ) )
			return false;
		unset ( $this->files[$name] );
		return true;
	}


	# - Getting / setting / cleaning entire params / files arrays - #

	public function nohooks__get_params ( $hook_object )
	{
		return $this->params;
	}

	public function nohooks__set_params ( $hook_object, $params )
	{
		if ( ! is_array ( $params ) )
			return false;

		$old_params = $this->params;
		$this->params = array();
		foreach ( $params as $name => $value )
		{
			if ( ! $this->set_param ( $name, $value ) )
			{
				$this->params = $old_params;
				return false;
			}
		}
		return true;
	}


	public function nohooks__clear_params ( $hook_object )
	{
		$this->params = array();
		return true;
	}


	public function nohooks__get_files ( $hook_object )
	{
		return $this->files;
	}

	public function nohooks__set_files ( $hook_object, $files )
	{
		$this->files = $files;
		return true;
	}

	public function nohooks__clear_files ( $hook_object )
	{
		$this->files = array();
		return true;
	}


	public function get_params ()
	{
		return $this->hooks->call (
			get_class ( $this ) . '::get_params',
			array ( $this, 'nohooks__get_params' ),
			$this
		);
	}


	public function set_params ( $params )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::set_params',
			array ( $this, 'nohooks__set_params' ),
			$this,
			$params
		);
	}


	public function clear_params ()
	{
		return $this->hooks->call (
			get_class ( $this ) . '::clear_params',
			array ( $this, 'nohooks__clear_params' ),
			$this
		);
	}


	public function get_files ()
	{
		return $this->hooks->call (
			get_class ( $this ) . '::get_files',
			array ( $this, 'nohooks__get_files' ),
			$this
		);
	}


	public function set_files ( $params )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::set_files',
			array ( $this, 'nohooks__set_files' ),
			$this,
			$params
		);
	}


	public function clear_files ()
	{
		return $this->hooks->call (
			get_class ( $this ) . '::clear_files',
			array ( $this, 'nohooks__clear_files' ),
			$this
		);
	}


	# - Obtaining params / files in HTTP request parameters format - #

	# Currently does not protest if a required param is not set.
	public function params ()
	{
		if ( ! $this->settings['lax_mode'] )
			if ( ! isset ( $this->paramdesc ) )
				return NULL;

		$params = array();
		foreach ( $this->params as $name => $value )
		{
			if ( is_array ( $value ) )
			{
				sort ( $value );
				$value = implode ( '|', $value );
			}

			if ( $value === true )
				$value = "";

			if ( $value === false )
				$value = NULL;

			if ( ! is_null ( $value ) )
			{
				if ( isset ( $this->paramdesc['params'][$name]['varname'] ) )
				{
					$name = $this->paramdesc['params'][$name]['varname'];
					if ( $name === "" )
						continue;
				}
				elseif ( isset ( $this->paramdesc['prefix'] ) )
				{
					$name = $this->paramdesc['prefix'] . $name;
				}
				$params[$name] = $value;
			}
		}

		$this->clear_params();

		return $params;
	}

	public function files ()
	{
		$files = $this->files;
		$this->clear_files();
		return $files;
	}


	# ----- Saving / Restoring params info ----- #

	public function save_params ()
	{
		$this->saved_params = $this->get_params();
		return true;
	}

	public function restore_params ()
	{
		return $this->set_params ( $this->saved_params );
	}


	# ----- Abstract ----- #

	abstract protected function paramdesc ();


}
