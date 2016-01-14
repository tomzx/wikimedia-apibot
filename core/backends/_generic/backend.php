<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backends: Generic: Backend.
#
#  Container for the base bot backend-specific objects.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../../common/browser.php' );
require_once ( dirname ( __FILE__ ) . '/../../common/hooks.php' );
require_once ( dirname ( __FILE__ ) . '/../../common/infostore.php' );
require_once ( dirname ( __FILE__ ) . '/../../common/log.php' );
require_once ( dirname ( __FILE__ ) . '/../../common/settings.php' );

require_once ( dirname ( __FILE__ ) . '/exchanger.php' );
require_once ( dirname ( __FILE__ ) . '/identity.php' );
require_once ( dirname ( __FILE__ ) . '/info.php' );


abstract class Backend
{

	# --- Objects --- #

	public $browser;
	public $hooks;
	public $infostore;
	public $log;
	public $settings;

	public $exchanger;
	public $identity;
	public $info;


	# --- State --- #


	protected $operable = true;  // false if backend itself becomes inoperable


	# --- Data --- #

	protected $wiki;
	protected $account;


	# ----- Constructor and destructor ----- #


	function __construct ( &$log, &$settings, &$infostore, &$hooks, &$browser )
	{
		$name = $this->backend_name();

		$this->browser = $browser;
		$this->hooks = $hooks;
		$this->log = $log;
		$this->settings = $settings;
		$this->infostore = $infostore;

		$this->wiki    = $this->settings->get ( "wiki" );
		$this->account = $this->settings->get ( "account" );

		if ( $this->account === NULL )
		{
			$this->log ( "No account info set - cannot use the $name backend!",
				LL_ERROR );
			$this->operable = false;
			return false;
		}
		elseif ( $this->wiki === NULL )
		{
			$this->log ( "No account info set - cannot use the $name backend!",
				LL_ERROR );
			$this->operable = false;
			return false;
		}
		elseif ( $this->wiki['software'] !== $this->backend_software() )
		{
			$this->log ( "The wiki " . $this->wiki['name'] . " is based on " .
				$this->wiki['software'] .
				"software - the $name backend will not work with it", LL_DEBUG );
			$this->operable = false;
			return false;
		}


		$this->create_backend_objects();

		$this->log ( $name . " backend is started.", LL_DEBUG );
	}


	function __destruct ()
	{
		$this->log ( $this->backend_name() . " backend is stopped.", LL_DEBUG );
	}


	# ----- Tools ----- #


	public function log ( $msg, $loglevel = LL_INFO, $logpreface = "" )
	{
		return $this->log->log ( $msg, $loglevel, $logpreface );
	}


	public function is_operable ()
	{
		return $this->operable && $this->exchanger->is_operable();
	}


	# ----- Creating objects ----- #


	protected function new_exchanger ( $url, $settings, $defaults,
		$log, $browser, $hooks )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::new_exchanger',
			array ( $this, 'nohooks__new_exchanger' ),
			$this,
			$url, $settings, $defaults, $log, $browser, $hooks
		);
	}


	protected function new_identity ( $exchanger, $infostore, $hooks,
		$settings, $backend_name )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::new_identity',
			array ( $this, 'nohooks__new_identity' ),
			$this,
			$exchanger, $infostore, $hooks, $settings, $backend_name
		);
	}


	protected function new_info ( $exchanger, $infostore, $identity,
		$hooks, $settings )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::new_info',
			array ( $this, 'nohooks__new_info' ),
			$this,
			$exchanger, $infostore, $identity, $hooks, $settings
		);
	}


	protected function new_tokens ( $exchanger, $info, $hooks, $settings )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::new_tokens',
			array ( $this, 'nohooks__new_tokens' ),
			$this,
			$exchanger, $info, $hooks, $settings
		);
	}


	protected function create_backend_objects ()
	{
		$name = $this->backend_name();

		$urls = $this->wiki['urls'];

		if ( ! isset ( $urls[strtolower( $name )] ) )
		{
			$this->log ( "No $name connection link set - " .
				"I will not be able to use the $name backend!",
				LL_WARNING );
			$this->is_operable = false;
		}

		$this->exchanger = $this->new_exchanger ( $name,
			$urls[strtolower ( $name )],
			$this->settings, $this->log, $this->browser, $this->hooks );

		$this->identity = $this->new_identity ( $this->exchanger, $this->infostore,
			$this->hooks, $this->settings, $name );

		$this->info = $this->new_info ( $this->exchanger, $this->infostore,
			$this->identity, $this->hooks, $this->settings );

		$this->exchanger->set_info ( $this->info );
	}


	# ----- Login and logout ----- #


	public function login ( $account )
	{
		return $this->identity->login ( $account );
	}

	public function logout ()
	{
		return $this->identity->logout();
	}


	# ----- Abstract ----- #


	abstract public function backend_name ();

	abstract public function backend_software ();

	abstract public function nohooks__new_exchanger ( $hook_object,
		$url, $settings, $defaults, $log, $browser, $hooks );
	abstract public function nohooks__new_identity ( $hook_object,
		$exchanger, $infostore, $hooks, $settings, $backend_name );
	abstract public function nohooks__new_info ( $hook_object,
		$exchanger, $infostore, $identity, $hooks, $settings );
	abstract public function nohooks__new_tokens ( $hook_object,
		$exchanger, $info, $hooks, $settings );


}
