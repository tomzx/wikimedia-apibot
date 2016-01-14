<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Core: Core.
#
#  Container for the base bot objects. Also, de facto the Apibot core.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/common/browser.php' );
require_once ( dirname ( __FILE__ ) . '/common/hooks.php' );
require_once ( dirname ( __FILE__ ) . '/common/info.php' );
require_once ( dirname ( __FILE__ ) . '/common/infostore.php' );
require_once ( dirname ( __FILE__ ) . '/common/log.php' );
require_once ( dirname ( __FILE__ ) . '/common/settings.php' );

require_once ( dirname ( __FILE__ ) . '/backends/api/backend.php' );
require_once ( dirname ( __FILE__ ) . '/backends/web/backend.php' );


define ( 'APIBOT_VERSION', "0.40.26" );



class Core
{

	# --- Objects --- #

	public $browser;
	public $hooks;
	public $infostore;
	public $log;
	public $settings;

	public $backends;

	public $info;


	# --- Internal usage --- #

	protected $cached_log = array();

	protected $backends_creating_functions = array (
		'api' => "backend_api",
		'web' => "backend_web",
	);


	# ----- Constructor and destructor ----- #

	function __construct ( $account, $settings = array(), $task_settings = array() )
	{
		if ( empty ( $account ) )
			die ( "The account is not set!\n" );

		if ( ! $this->check_system ( $settings ) )
			die ( "Cannot work in this system - it does not match my requirements.\n" );

		$this->settings = new Bot_Settings ( $account, $settings, $task_settings );

		$this->set_environment ( $this->settings->get ( "environment" ) );

		$this->hooks = new Hooks ( $this->settings->get ( "hooks" ) );

		$this->scan_extensions();

		$this->log = $this->log_object ( $this );
		$this->log ( "Started, Apibot " . APIBOT_VERSION );
		$this->flush_cached_log ( $this->log );

		$this->browser = $this->browser_object ( $this );

		$this->infostore = $this->infostore_object ( $account, $this );

		$this->backends = $this->create_backends ( $this );

		$this->info = $this->info_object ( $this );

		$this->log ( "Core created for " . $this->settings->get ( 'account', 'user' )
			. "@" . $this->settings->get ( 'wiki', 'name' ) . ".", LL_DEBUG );

		$operable_backends = $this->operable_backends_names();
		if ( empty ( $operable_backends ) )
		{
			$this->log ( "No operable backends present - cannot work with a wiki!",
				LL_ERROR );
			$this->log ( "(However, you might still be able to do some local work)",
				LL_INFO );
		}
		else
		{
			$this->log ( "Operable backends: " . implode ( ", ", $operable_backends ),
				LL_DEBUG );
		}
	}


	function __destruct ()
	{
		$this->close_info ( $this );

		$this->close_backends ( $this );

		$this->log ( "Ended, Apibot " . APIBOT_VERSION );
	}


	# ----- Checking the system ----- #


	protected function check_system ( $settings = array() )
	{
		return true;
	}


	# ----- Tools ----- #

	protected function cache_logline ( $msg, $loglevel = LL_INFO,
		$logpreface = "" )
	{
		$this->cached_log[] =
			array ( 'msg' => $msg, 'll' => $loglevel, 'lp' => $logpreface );
	}

	protected function flush_cached_log ( $log_object )
	{
		foreach ( $this->cached_log as $logline )
			$log_object->log ( $logline['msg'], $logline['ll'], $logline['lp'] );
	}


	public function log ( $msg, $loglevel = LL_INFO, $logpreface = "" )
	{
		if ( isset ( $this->log ) )
			$this->log->log ( $msg, $loglevel, $logpreface );
		else
			$this->cache_logline ( $msg, $loglevel, $logpreface );
	}


	public function version ()
	{
		return APIBOT_VERSION;
	}


	# ----- Settings ----- #

	protected function set_environment ( $settings )
	{
		if ( isset ( $settings['timezone'] ) )
			$tz = $settings['timezone'];

		if ( ! isset ( $tz ) )
			$tz = getenv ( 'TZ' );

		if ( empty ( $tz ) )
		{
			$tz = 'UTC';
			$this->cache_logline (
				"Local timezone must be set in php.ini or the bot settings. " .
				"Will use 'UTC' meanwhile.", LL_WARNING );
		}

		date_default_timezone_set ( $tz );

		if ( isset ( $settings['memory_limit'] ) )
			ini_set ( 'memory_limit', $settings['memory_limit'] );
	}


	# ----- Objects ----- #


	public function nohooks__log_object ( $hook_object, $core )
	{
		return new Log ( $core->settings->get ( "log" ) );
	}


	public function nohooks__browser_object ( $hook_object, $core )
	{
		return new Browser ( $core->settings->get ( "browser" ) );
	}


	public function nohooks__infostore_object ( $hook_object, $account, $core )
	{
		return new Infostore ( $account['wiki']['name'], $account['user'],
			$core->settings );
	}


	public function nohooks__create_backends ( $hook_object, $core )
	{
		$backends = array();

		$wiki_software = $this->settings->get ( 'wiki', 'software' );

		foreach ( $this->backends_creating_functions as $function )
		{
			$backend = call_user_func ( array ( $this, $function ), $core->log, $core->settings, $core->infostore, $core->hooks, $core->browser );
			if ( $backend->backend_software() == $wiki_software )
				$backends[$backend->backend_name()] = $backend;
		}

		return $backends;
	}


	public function nohooks__close_backends ( $hook_object, $core )
	{
		unset ( $core->backends );
	}


	public function nohooks__backend_api ( $hook_object,
		$log, $settings, $infostore, $hooks, $browser )
	{
		return new Backend_API ( $log, $settings, $infostore, $hooks, $browser );
	}


	public function nohooks__backend_web ( $hook_object,
		$log, $settings, $infostore, $hooks, $browser )
	{
		return new Backend_Web ( $log, $settings, $infostore, $hooks, $browser );
	}


	public function nohooks__info_object ( $hook_object, $core )
	{
		return new Info ( $core );
	}


	public function nohooks__close_info ( $hook_object, $core )
	{
		unset ( $core->info );
	}


	protected function log_object ( $core )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::log_object',
			array ( $this, 'nohooks__log_object' ),
			$this, $core
		);
	}


	protected function browser_object ( $core )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::browser_object',
			array ( $this, 'nohooks__browser_object' ),
			$this, $core
		);
	}


	protected function infostore_object ( $account, $core )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::infostore_object',
			array ( $this, 'nohooks__infostore_object' ),
			$this, $account, $core
		);
	}


	protected function create_backends ( $core )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::create_backends',
			array ( $this, 'nohooks__create_backends' ),
			$this, $core
		);
	}


	protected function close_backends ( $core )
	{
		if ( isset ( $this->hooks ) )
		return $this->hooks->call (
			get_class ( $this ) . '::close_backends',
			array ( $this, 'nohooks__close_backends' ),
			$this, $core
		);
	}


	protected function backend_api ( $log, $settings, $infostore, $hooks,
		$browser )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::backend_api',
			array ( $this, 'nohooks__backend_api' ),
			$this, $log, $settings, $infostore, $hooks, $browser
		);
	}


	protected function backend_web ( $log, $settings, $infostore, $hooks,
		$browser )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::backend_web',
			array ( $this, 'nohooks__backend_web' ),
			$this, $log, $settings, $infostore, $hooks, $browser
		);
	}


	protected function info_object ( $core )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::info_object',
			array ( $this, 'nohooks__info_object' ),
			$this, $core
		);
	}


	protected function close_info ( $core )
	{
		if ( isset ( $this->hooks ) )
		return $this->hooks->call (
			get_class ( $this ) . '::close_info',
			array ( $this, 'nohooks__close_info' ),
			$this, $core
		);
	}


	# ----- Scanning hooks, extensions etc ----- #


	protected function scan_extensions ()
	{
		$extdir = dirname ( __FILE__ ) . '/../../extensions';

		if ( @is_dir ( $extdir ) )
		{

			$hooks_before = $extdir . '/hooks_before.php';
			if ( @file_exists ( $hooks_before ) )
				require_once ( $hooks_before );

			if ( @file_exists ( $extdir . '/extensions.php' ) )
			{
				require_once ( $extdir . '/extensions.php' );
			}
			else
			{
				$dirlist = @scandir ( $extdir );
				if ( $dirlist !== false )
				{
					foreach ( $dirlist as $entry )
						if ( @is_dir ( $entry ) && ! ( substr ( $entry, 0, 1 ) == '.' ) )
						{
							$rootfile = $extdir . '/' . $entry . '/' . $entry . '.php';
							if ( @file_exists ( $rootfile ) )
							{
								require_once ( $rootfile );
								// todo! when central extensions registration is made:
								// check here for a global var set by the extension with its desc
								// if set, fetch the extension data from it and unset it
							}
						}
				}
			}

			$hooks_after = $extdir . '/hooks_after.php';
			if ( @file_exists ( $hooks_after ) )
				require_once ( $hooks_after );
		}

	}


	# ----- Backends ----- #


	public function backend ( $name )
	{
		return ( isset ( $this->backends[$name] ) ? $this->backends[$name] : NULL );
	}


	public function available_backends_names ()
	{
		return array_keys ( $this->backends_creating_functions );
	}


	public function active_backends_names ()
	{
		return array_keys ( $this->backends );
	}


	public function operable_backends_names ()
	{
		$backends = array();

		foreach ( $this->backends as $name => $backend )
			if ( $backend->is_operable() )
				$backends[] = $name;

		return $backends;
	}


}
