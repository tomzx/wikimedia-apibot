<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backends: Generic: Identity.
#
#  Provides login, logout and other identity management.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../../common/hooks.php' );
require_once ( dirname ( __FILE__ ) . '/../../common/infostore.php' );
require_once ( dirname ( __FILE__ ) . '/../../common/settings.php' );

require_once ( dirname ( __FILE__ ) . '/exchanger.php' );


class ApibotException_LoginError extends ApibotException_TaskClose
{
	function __construct ( $error )
	{
		parent::__construct (
			"loginerror",
			"Login error: " . $error
		);
	}
}



abstract class Identity
{

	protected $hooks;
	protected $infostore;
	protected $settings;

	protected $exchanger;


	protected $backend_name;

	protected $cookies_names;

	protected $wiki;
	protected $account;

	protected $logged_in = false;


	public $always_login   = false;
	public $login_attempts = 5;


	# ----- Constructor ----- #


	function __construct ( &$exchanger, &$infostore, &$hooks, &$settings,
		$backend_name, $params = NULL )
	{
		$this->exchanger = $exchanger;
		$this->infostore = $infostore;
		$this->hooks = $hooks;
		$this->settings = $settings;

		$this->backend_name = $backend_name;

		if ( is_array ( $params ) )
			foreach ( $params as $name => $value )
				$this->$name = $value;
	}


	# ----- Tools ----- #


	protected function log ( $message, $loglevel = LL_INFO, $logpreface = NULL )
	{
		return $this->exchanger->log ( $message, $loglevel, $logpreface );
	}


	# ----- Identity cookies management ----- #


	protected function get_identity_cookies ()
	{
		if ( empty ( $this->cookies_names ) )
			return false;

		$cookies = array();
		foreach ( $this->cookies_names as $name )
		{
			$value = $this->exchanger->get_cookie ( $name );
			if ( $value !== false )
				$cookies[$name] = $value;
		}

		if ( empty ( $cookies ) )
			return false;
		else
			return $cookies;
	}

	protected function set_identity_cookies ( $cookies, $expiration = NULL )
	{
		if ( ! is_null ( $expiration ) )
			$expiration += time();

		$this->cookies_names = array();
		foreach ( $cookies as $name => $value )
		{
			$this->cookies_names[] = $name;
			$this->exchanger->set_cookie ( $name, $value );
			if ( ! is_null ( $expiration ) )
				$this->exchanger->set_cookie_expiration ( $name, $expiration );
		}
	}

	protected function unset_identity_cookies ()
	{
		foreach ( $this->cookies_names as $name )
			$this->exchanger->del_cookie ( $name );
	}


	protected function read_identity_cookies ( $account, $wiki )
	{
		$cookies = $this->infostore->read_identity ( "cookies" );
		if ( $cookies === false )
		{
			return false;
		}
		else
		{
			foreach ( $cookies as $cookie )
				if ( $cookie['exp'] < time() )
					return false;
			$this->set_identity_cookies ( $cookies );
			$this->log ( "Cookie-identifying at " . $wiki['name'] .
				" as " . $account['user'], LL_INFO );
			return true;
		}
	}

	protected function write_identity_cookies ()
	{
		$cookies = $this->get_identity_cookies();
		if ( $cookies !== false )
			return $this->infostore->write_identity ( "cookies", $cookies );
	}

	protected function delete_identity_cookies ()
	{
		$this->unset_identity_cookies();
		$this->infostore->del_identity ( "cookies" );
		return true;
	}


	# ----- Public ----- #


	public function logged_in ()
	{
		return $this->logged_in;
	}


	public function login ( $account = NULL )
	{
		if ( $this->logged_in )
			$this->logout();

		if ( is_null ( $account ) )
			$account = $this->settings->get ( 'account' );

		$this->account = $account;

		$this->wiki = $this->settings->get ( "wiki" );

		if ( empty ( $this->account ) )
		{
			$this->log ( "Fatal error: No account specified!", LL_PANIC );
			die();
		}

		if ( ! $this->always_login &&
			$this->read_identity_cookies ( $this->account, $this->wiki ) )

			$this->logged_in = true;

		else

			if ( $this->full_login ( $this->account, $this->wiki ) )
				$this->logged_in = $this->write_identity_cookies();
			else
				$this->logged_in = false;

		return $this->logged_in;
	}


	public function logout ()
	{
		if ( ! $this->logged_in )
			return true;

		if ( $this->always_login )
		{
			if ( $result = $this->full_logout() )
				$this->delete_identity_cookies();
			return $result;
		}
		else
		{
			$this->unset_identity_cookies();
		}

		return true;
	}


	# ----- Abstract ----- #


	# Must be used in full_login(), as every wiki has its own prefix
	abstract protected function set_cookies_names ( $cookieprefix );


	abstract protected function full_login ( $account, $wiki );

	abstract protected function full_logout ();


}
