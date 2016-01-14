<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backends: Generic: Tokens.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../../common/hooks.php' );
require_once ( dirname ( __FILE__ ) . '/../../common/settings.php' );

require_once ( dirname ( __FILE__ ) . '/exchanger.php' );
require_once ( dirname ( __FILE__ ) . '/info.php' );



abstract class Tokens
{

	protected $hooks;
	protected $settings;

	protected $exchanger;
	protected $info;

	private $tokens = array();


	# ----- Constructor ----- #


	function __construct ( $exchanger, $info, $hooks, $settings )
	{
		$this->exchanger = $exchanger;
		$this->info = $info;
		$this->hooks = $hooks;
		$this->settings = $settings;
	}


	# ----- Tools ----- #


	protected function log ( $msg, $loglevel = LL_INFO, $preface = "tokens: " )
	{
		return $this->exchanger->log ( $msg, $loglevel, $preface );
	}


	# ----- Tokens management ----- #


	protected function token_exists ( $name, $id = NULL )
	{
		if ( $id === NULL )
			return isset ( $this->tokens[$name] );
		else
			return isset ( $this->tokens[$name][$id] );
	}


	protected function get_token ( $name, $id = NULL )
	{
		if ( $id === NULL )
			return ( isset ( $this->tokens[$name] )
				? $this->tokens[$name]
				: NULL );
		else
			return ( isset ( $this->tokens[$name][$id] )
				? $this->tokens[$name][$id]
				: NULL );
	}


	protected function set_token ( $token, $name, $id = NULL )
	{
		if ( $id === NULL )
			$this->tokens[$name] = $token;
		else
		{
			if ( ! isset ( $this->tokens[$name] ) )
				$this->tokens[$name] = array();
			$this->tokens[$name][$id] = $token;
		}

		return $token;
	}


	protected function unset_token ( $name, $id = NULL )
	{
		$token = $this->get_token ( $name, $id );

		if ( $id === NULL )
		{
			if ( isset ( $this->tokens[$name] ) )
				unset ( $this->tokens[$name] );
		}
		else
		{
			if ( isset ( $this->tokens[$name][$id] ) )
				unset ( $this->tokens[$name][$id] );

			if ( empty ( $this->tokens[$name] ) )
				unset ( $this->tokens[$name] );
		}

		return $token;
	}


}
