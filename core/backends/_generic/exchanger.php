<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backends: Generic: Exchanger.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #

require_once ( dirname ( __FILE__ ) . '/../../common/browser.php' );
require_once ( dirname ( __FILE__ ) . '/../../common/hooks.php' );
require_once ( dirname ( __FILE__ ) . '/../../common/log.php' );



# ---------------------------------------------------------------------------- #
# --                           Generic Exchanger                            -- #
# ---------------------------------------------------------------------------- #


class ApibotException_NoExchURL extends ApibotException_TaskClose
{
	function __construct ()
	{
		parent::__construct (
			"noexchurl",
			"No URL to make requests to specified"
		);
	}
}

class ApibotException_BadContentType extends ApibotException_AccessRetry
{
	function __construct ()
	{
		parent::__construct (
			"badcontenttype",
			"Received from the wiki inappropriate content type"
		);
	}
}

class ApibotException_NotData extends ApibotException_AccessRetry
{
	function __construct ( $info )
	{
		parent::__construct (
			"notdata",
			$info
		);
	}
}

class ApibotException_XferNoReply extends ApibotException_AccessRetry
{
	function __construct ()
	{
		parent::__construct (
			"xfernoreply",
			"Zero size reply received"
		);
	}
}

class ApibotException_ServerError extends ApibotException_AccessRetry
{
	function __construct ( $error, $info = NULL )
	{
		parent::__construct (
			$error,
			( is_null ( $info ) ? "" : $info )
		);
	}
}


class ApibotException_ExchangeLag extends ApibotException_AccessRetry
{
	public $lagsecs;

	function __construct ( $info, $lagsecs )
	{
		parent::__construct (
			"exchangelag",
			$info
		);
		$this->lagsecs = $lagsecs;
	}
}



abstract class Exchanger
{

	# --- Data --- #

	public $data;   // data returned by the request (possibly tidied)

	# --- State --- #

	protected $operable = true;

	# --- Parameters --- #

	protected $backend_name;
	protected $url;
	protected $settings;
	protected $params;

	# --- Service objects --- #

	protected $browser;  // the wiki browser object
	protected $log;      // the logging object
	protected $hooks;    // the hooks support object


	# ----- Constructor ----- #

	function __construct ( $backend_name, $url, $settings, $log, $browser, $hooks )
	{
		if ( empty ( $url ) )
			throw new ApibotException_NoExchURL();

		$this->backend_name = $backend_name;
		$this->url          = $url;
		$this->settings =
			$settings->get_withbackend ( $backend_name, 'exchanger', 'settings' );
		$this->params =
			$settings->get_withbackend ( $backend_name, 'exchanger', 'params' );

		$this->browser = $browser;
		$this->log     = $log;
		$this->hooks   = $hooks;

		$this->set_params ( $this->params );

		if ( ! isset ( $this->settings['max_retries'] ) )
			$this->settings['max_retries'] = 5;
		if ( ! isset ( $this->settings['retry_wait'] ) )
			$this->settings['retry_wait'] = 1;
	}


	# ----- Tools ----- #

	public function log ( $msg, $loglevel = LL_INFO, $preface = "exchanger: " )
	{
		if ( is_object ( $this->log ) )
			$this->log->log ( $msg, $loglevel, $preface );
	}


	private function retry_wait ( $retry_no )
	{
		if ( is_array ( $this->settings['retry_wait'] ) )
			if ( isset ( $this->settings['retry_wait'][$retry_no] ) )
			{
				$secs = $this->settings['retry_wait'][$retry_no];
			}
			else
			{
				$secs = $retry_no * $retry_no;
			}
		else
		{
			$secs = $this->settings['retry_wait'] * $retry_no * $retry_no;
		}
		return $secs;
	}


	# ----- Data transfers ----- #

	public function nohooks__browse ( $hook_object,
		$uri, $vars = array(), $files = array(), $mustbeposted = false )
	{
		if ( $this->settings['dump_level'] > 0 )
		{
			echo "Vars: " ; var_dump ( $vars );
			echo "Files: "; var_dump ( $files );
		}

		unset ( $this->data );

		$retry_no = 0;
		while ( $retry_no < $this->settings['max_retries'] )
		{
			sleep ( $this->retry_wait ( $retry_no ) );

			if ( isset ( $exception ) )
				unset ( $exception );

			try
			{
				$this->browser->xfer ( $uri, $vars, $files, $mustbeposted );
			}
			catch ( ApibotException $exception )
			{
			}

			if ( ! isset ( $exception ) )
			{

				if ( strlen ( $this->browser->content ) == 0 )
				{
					$exception = new ApibotException_XferNoReply();
				}
				else
				{
					$process_result = $this->process_reply();

					if ( is_object ( $process_result ) )  // must be exception or true
					{
						$exception = $process_result;
					}
					else
					{

						$this->browser->flush();
						if ( $this->settings['dump_level'] > 0 )
						{
							echo "Data: ";
							var_dump ( $this->data );
						}

						if ( $process_result )
						{
							$this->operable = true;
							$this->error = NULL;
						}
						else
						{
							if ( ! isset ( $this->data['error'] ) )
								$this->data['error'] = array (
									'range'  => AEX_RANGE_ACCESS,
									'advice' => AEX_ADVICE_RETRY,
									'code'   => "bad_data",
									'info'   => "Received data could not be processed",
								);
						}

						return $process_result;
					}

				}

			}

			if ( isset ( $exception ) )
			{

				if ( ! isset ( $this->data['error'] ) )
					$this->data['error'] = array (
						'range'  => $exception->range,
						'advice' => $exception->advice,
						'code'   => $exception->code,
						'info'   => $exception->info,
					);

				switch ( get_class ( $exception ) )
				{

					case "ApibotException_ExchangeLag" :
						$this->log ( $exception->info . " - will wait " .
							$exception->lagsecs . " secs...", LL_DEBUG );
						sleep ( $exception->lagsecs );
						$retry_no--;
						break;

					case "ApibotException_NotData" :
						$this->log ( "The site result does not look like data: " .
							print_r ( $this->data, true ), LL_DEBUG );
						break;

					case "ApibotException_HTTP404" :
						$this->log ( "Site returned HTTP error 404 - is the URL (" .
							$this->url . ") correct?", LL_DEBUG );
						break;
				}

				if ( $exception->advice < AEX_ADVICE_RETRY )
				{
					if ( ! $exception instanceof ApibotException_Access )
						$this->operable = false;
					throw $exception;
				}

			}
			$retry_no++;
			$this->log ( "Data transfer failed (" . $exception->info .
				") - retry " . $retry_no . "...",
				LL_WARNING );
		}

		return false;
	}


	public function nohooks__xfer ( $hook_object,
		$vars, $files = array(), $mustbeposted = false )
	{
		$vars = array_merge ( $this->get_params(), $vars );

		return $this->browse ( $this->url, $vars, $files, $mustbeposted );
	}


	# ----- Browser functions export ----- #

	public function last_time()
	{
		return $this->browser->last_time();
	}

	public function bytecounters ()
	{
		return $this->browser->bytecounters;
	}

	public function reset_bytecounters ()
	{
		return $this->browser->reset_bytecounters();
	}

	public function get_headers ()
	{
		return $this->browser->headers;
	}

	public function get_cookies ()
	{
		return $this->browser->cookies;
	}

	public function get_cookie ( $cookie_name )  // often array ( 'content' =>, 'exp' )
	{
		return $this->browser->get_cookie ( $cookie_name );
	}

	public function set_cookie ( $cookie_name, $cookie )
	{
		return $this->browser->set_cookie ( $cookie_name, $cookie );
	}

	public function del_cookie ( $cookie_name )
	{
		return $this->browser->del_cookie ( $cookie_name );
	}

	public function set_cookie_expiration ( $cookie_name, $secs )
	{
		return $this->browser->set_cookie_expiration ( $cookie_name, $secs );
	}

	public function modify_cookie_expiration ( $cookie_name, $secs_diff )
	{
		return $this->browser->modify_cookie_expiration ( $cookie_name, $secs_diff );
	}


	# ----- Setting params ----- #


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
			$this, $params
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


	# ----- Processing replies ----- #


	protected function process_reply ()
	{
		return $this->hooks->call (
			get_class ( $this ) . '::process_reply',
			array ( $this, 'nohooks__process_reply' ),
			$this
		);
	}


	# ----- Data transfers (with hooks) ----- #


	public function browse ( $uri, $vars = array(), $files = array(),
		$mustbeposted = false )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::browse',
			array ( $this, 'nohooks__browse' ),
			$this,
			$uri, $vars, $files, $mustbeposted
		);
	}


	public function xfer ( $vars, $files = array(), $mustbeposted = false )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::xfer',
			array ( $this, 'nohooks__xfer' ),
			$this,
			$vars, $files, $mustbeposted
		);
	}


	# ----- Status ----- #


	public function is_operable ()
	{
		return $this->operable;
	}


	# ----- Abstract ----- #

	# Descendants should set $this->data
	# Descendants should return true (OK) or an appropriate ApibotException object
	abstract public function nohooks__process_reply ( $hook_object );

	abstract public function nohooks__get_params ( $hook_object );
	abstract public function nohooks__set_params ( $hook_object, $params );
	abstract public function nohooks__clear_params ( $hook_object );

	abstract public function set_info ( &$info );

}
