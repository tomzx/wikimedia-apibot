<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backends: Generic: Info.
#
#  Generic Info fetching and info direct access functions.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../../common/hooks.php' );
require_once ( dirname ( __FILE__ ) . '/../../common/infostore.php' );
require_once ( dirname ( __FILE__ ) . '/../../common/settings.php' );

require_once ( dirname ( __FILE__ ) . '/exchanger.php' );
require_once ( dirname ( __FILE__ ) . '/identity.php' );



abstract class Info_Generic
{

	public $autoload = true;


	# ----- Internal ----- #

	protected $hooks;
	protected $infostore;
	protected $settings;

	protected $exchanger;
	protected $identity;

	protected $info_settings;  // as an array


	# ----- Constructor ----- #

	function __construct ( &$exchanger, &$infostore, &$identity, &$hooks,
		&$settings, $force_load = false )
	{
		$this->exchanger = $exchanger;
		$this->hooks     = $hooks;
		$this->identity  = $identity;
		$this->infostore = $infostore;
		$this->settings  = $settings;

		$this->info_settings = $this->settings->get ( 'info' );
		if ( empty ( $this->info_settings['infotypes'] ) )
			$this->info_settings['infotypes'] = $this->default_info_settings();

		if ( $force_load )
			$this->load_all_info();
	}


	# ----- Tools ----- #


	public function log ( $message, $loglevel = LL_INFO, $preface = "info: " )
	{
		$this->exchanger->log ( $message, $loglevel, $preface );
	}


	protected function xfer ( $params )
	{
		if ( $this->exchanger->is_operable() )
			return $this->exchanger->xfer ( $params );
		else
			return NULL;
	}


# ---------------------------------------------------------------------------- #
# --               Reading / Writing info from / to disk                    -- #
# ---------------------------------------------------------------------------- #


	protected function infofile_name ( $type )
	{
		return $type . "." . $this->backend_name() . ".info";
	}


	protected function exists_infofile ( $type )
	{
		if ( $type == "user" || $type == "globaluser" )
			return $this->infostore->exists_userinfo ( $this->infofile_name ( $type ) );
		else
			return $this->infostore->exists_siteinfo ( $this->infofile_name ( $type ) );
	}

	protected function mtime_infofile ( $type )
	{
		if ( $type == "user" || $type == "globaluser" )
			return $this->infostore->mtime_userinfo ( $this->infofile_name ( $type ) );
		else
			return $this->infostore->mtime_siteinfo ( $this->infofile_name ( $type ) );
	}

	protected function read_infofile ( $type )
	{
		if ( $type == "user" || $type == "globaluser" )
			return $this->infostore->read_userinfo ( $this->infofile_name ( $type ) );
		else
			return $this->infostore->read_siteinfo ( $this->infofile_name ( $type ) );
	}

	protected function write_infofile ( $type, $info )
	{
		if ( $type == "user" || $type == "globaluser" )
			return $this->infostore->write_userinfo ( $this->infofile_name ( $type ),
				$info );
		else
			return $this->infostore->write_siteinfo ( $this->infofile_name ( $type ),
				$info );
	}


# ---------------------------------------------------------------------------- #
# --                       Loading and clearing info                        -- #
# ---------------------------------------------------------------------------- #


	protected function clear_loaded_info ( $type )
	{
		unset ( $this->info[$type] );
	}

	protected function clear_all_loaded_info ()
	{
		$this->info = array();
	}


	protected function load_info ( $type )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::load_info',
			array ( $this, 'nohooks__load_info' ),
			$this,
			$type
		);
	}


	# ----- Abstract ----- #


	abstract public function nohooks__load_info ( $hook_object, $type );

	abstract protected function load_all_info ();


	abstract protected function backend_name ();

	abstract protected function default_info_settings ();


# ---------------------------------------------------------------------------- #
# --                                                                        -- #
# --                         Info direct access                             -- #
# --                                                                        -- #
# ---------------------------------------------------------------------------- #


# ---------------------------------------------------------------------------- #
# --                             Global info                                -- #
# ---------------------------------------------------------------------------- #


	# ----- Global ----- #

	# --- Elements --- #

	protected function element_sub ( $element, $key )
	{
		if ( isset ( $element[$key] ) )
		{
			if ( is_null ( $element[$key] ) )
				return "";
			else
				return $element[$key];

		}
		else
		{
			return NULL;
		}
	}

	protected function element_sub_isset ( $element, $key )
	{
		return ( is_array ( $element )
			? isset ( $element[$key] )
			: NULL );
	}

	protected function element_arraykeys ( $element )
	{
		return ( is_array ( $element )
			? array_keys ( $element )
			: NULL );
	}

	protected function element_subs_count ( $element )
	{
		return ( is_array ( $element )
			? count ( $element )
			: NULL );
	}

	protected function sub_in_element ( $element, $sub )
	{
		if ( is_array ( $element ) )
			return in_array ( $sub, $element );
		return NULL;
	}


	# --- Infos --- #

	public function infotype ( $type )
	{
		if ( ! isset ( $this->info[$type] ) )
			$this->load_info ( $type );

		if ( isset ( $this->info[$type] ) && is_array ( $this->info[$type] ) )
			return $this->info[$type];

		return NULL;
	}

	public function infotype_isset ( $type )
	{
		$info = $this->infotype ( $type ); // don't optimize it away - loads the info
		return is_array ( $info );
	}


	public function infotype_element ( $type, $key )
	{
		$info = $this->infotype ( $type );
		return $this->element_sub ( $info, $key );
	}

	public function infotype_element_isset ( $type, $key )
	{
		$info = $this->infotype ( $type );
		return $this->element_sub_isset ( $info, $key );
	}

	public function infotype_element_arraykeys ( $type, $key )
	{
		$info = $this->infotype_element ( $type, $key );
		return $this->element_arraykeys ( $info );
	}

	public function infotype_element_subs_count ( $type, $key )
	{
		$info = $this->infotype_element ( $type, $key );
		return $this->element_subs_count ( $info );
	}

	public function infotype_sub ( $type, $element_key, $sub_key )
	{
		$element = $this->infotype_element ( $type, $element_key );
		return $this->element_sub ( $element, $sub_key );
	}


	# --- Indexes --- #

	public function indextype ( $type )
	{
		if ( ! isset ( $this->info[$type] ) )
			$this->load_info ( $type );

		if ( isset ( $this->index[$type] ) && is_array ( $this->index[$type] ) )
			return $this->index[$type];

		return NULL;
	}


	public function indextype_element ( $type, $key )
	{
		$index = $this->indextype ( $type );
		return $this->element_sub ( $index, $key );
	}

	public function indextype_element_isset ( $type, $key )
	{
		$index = $this->indextype ( $type );
		return $this->element_sub_isset ( $index, $key );
	}


}
