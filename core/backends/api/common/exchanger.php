<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Backend: Exchanger.
#  (Using and utilizing also the API mainmodule params.)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../../_generic/exchanger.php' );
require_once ( dirname ( __FILE__ ) . '/../mainmodule/params.php' );



class ApibotException_CantViewAllServersInfo extends ApibotException_AccessClose
{
}




# Still doesn't set exchange format - will default to xmlfm!
class Exchanger_API extends Exchanger
{

	protected $parm;          // the mainmodule params class


	public $default_format = "php";  // very old php versions don't speak JSON


	# ----- Constructor ----- #

	function __construct ( $backend_name, $url, $settings, $log, $browser, $hooks )
	{
		$this->backend_name = $backend_name;

		# Wiki info might be still inaccessible here - use the hardcoded paramdesc!
		$this->parm = new API_Params_Mainmodule ( $hooks, NULL,
			$settings->get_withbackend ( $backend_name, 'exchanger', 'settings' ) );

		$default_lax_mode = $this->parm->settings['lax_mode'];
		$this->parm->settings['lax_mode'] = true;

		parent::__construct ( $backend_name, $url, $settings, $log, $browser, $hooks );

		$this->parm->settings['lax_mode'] = $default_lax_mode;

		$this->exchanger_params =
			$settings->get_withbackend ( $backend_name, 'exchanger', 'defaults' );
	}


	# ----- Overriding ----- #

	public function xfer ( $uri, $vars = array(), $files = array(),
		$mustbeposted = false )
	{
		foreach ( $this->exchanger_params as $name => $value )
			if ( $this->parm->is_paramname_ok ( $name ) )
				$this->parm->set_param ( $name, $value );

		if ( ! $this->parm->param_isset ( 'format' ) )
			$this->set_param ( 'format', $this->default_format );

		return parent::xfer ( $uri, $vars, $files, $mustbeposted );
	}


	# ----- Implemented ----- #


	# --- Processing params --- #


	public function nohooks__get_params ( $hook_object )
	{
		return $this->parm->get_params();
	}

	public function nohooks__set_params ( $hook_object, $params )
	{
		return $this->parm->set_params ( $params );
	}

	public function nohooks__clear_params ( $hook_object )
	{
		return $this->parm->clear_params();
	}


	# --- Processing reply --- #


	protected function lagsecs ()
	{
		$lagsecs = $this->browser->find_header ( 'Retry-After' );
		if ( $lagsecs === false )  // no such header - set some default
			$lagsecs = 5;
		return $lagsecs;
	}


	protected function parse_reply_php ()
	{
		$this->data = @unserialize ( $this->browser->content );
		if ( $this->data === false )
			$this->data = NULL;
	}


	protected function parse_reply_json ()
	{
		$this->data = json_decode ( $this->browser->content, true );
	}


	protected function process_api_error ()
	{
		if ( is_array ( $this->data['error'] ) )
		{
			if ( isset ( $this->data['error']['code'] ) )
			{
				if ( $this->data['error']['code'] == "maxlag" )
					// since about v1.25 MW does not anymore return X-Database-Lag header
					return new ApibotException_ExchangeLag (
						$this->data['error']['info'], $this->lagsecs() );
				elseif ( $this->data['error']['code'] == "siincludeAllDenied" )
					return new ApibotException_CantViewAllServersInfo (
						$this->data['error']['code'], $this->data['error']['info'] );
				else
					return new ApibotException_ServerError (
						$this->data['error']['code'], $this->data['error']['info'] );
			}
			else
			{
				return new ApibotException_ServerError ( "API error: ",
					( isset ( $this->data['error']['info'] )
						? $this->data['error']['info']
						: "No info supplied" ) );
			}
		}
		else
		{
			return new ApibotException_ServerError (
				"Error message", $this->data['error'] );
		}

	}


	public function nohooks__process_reply ( $hook_object )
	{
		$format = $this->parm->get_param ( 'format' );
		switch ( $format )
		{
			case 'php' :
				$this->parse_reply_php();
				break;
			case 'json' :
				$this->parse_reply_json();
				break;
			// add parsing other formats here
			default :
				return new ApibotException_InternalError ( "Bad or unsupported data format: " .
					$format );
		}

		$header = $this->browser->find_header ( 'X-Database-Lag' );
		if ( $header !== false )
		{
			if ( isset ( $this->data['error']['info'] ) )
				$info = $this->data['error']['info'];
			return new ApibotException_ExchangeLag ( $info, $this->lagsecs() );
		}

		$header = $this->browser->find_header ( 'MediaWiki-API-Error' );
		if ( $header != false )
			return $this->process_api_error();

		$header = $this->browser->find_header ( 'Content-Type' );
		switch ( $format )
		{
			case "json" :
				if ( strpos ( $header, 'application/json' ) === false )
					return new ApibotException_BadContentType();
				break;
			case "php" :
				if ( strpos ( $header, 'application/vnd.php.serialized' ) === false )
					return new ApibotException_BadContentType();
				break;
		}

		if ( ! isset ( $this->data ) || is_null ( $this->data ) )
		{

			if ( preg_match ( '/Unexpected non-MediaWiki exception encountered\, of type \&quot\;(.*)\&quot\;\<br \/\>(.*)\<br \/\>/Uus', $this->browser->content, $matches ) )
			{
				$error = trim ( $matches[1] ) . " (" . trim ( $matches[2] );
				if ( preg_match ( '/^unknown_action:/U', trim ( $matches[2] ), $matches ) )
					$error .= ' (is $wgEnableWriteAPI enabled on this wiki?)';
			}
			elseif ( preg_match ( "/\<b\>\s*Fatal error\s*\<\/b\>\s*\:(.*)$/Usi",
				$this->browser->content, $matches ) )
			{
				$error = "Fatal error: " . trim ( $matches[1] );  // could be processed further
			}
			elseif ( preg_match ( '/\<title\>([^\<]+)\<\/title\>/ui',
				$this->browser->content, $matches ) )
			{
				$error = "Technical problem: " . $matches[1];
			}
			else
			{
				$error = "Unidentified problem while receiving data!";
			}

			if ( $this->dump_level >= 2 )
				var_dump ( $this->browser->content );

			return new ApibotException_ServerError ( "No data received", $error );

		}
		elseif ( ! is_array ( $this->data ) )  // should not occur, but just in case...
		{

			if ( $this->dump_level >= 2 )
				var_dump ( $this->browser->content );
			return new ApibotException_NotData ( $this->data );

		}
		elseif ( isset ( $this->data['error'] ) )
		{
			return $this->process_api_error();
		}

		return true;
	}


	# --- Setting actual info class --- #
	# (after the wiki info is obtained)

	public function set_info ( &$info )
	{
		$this->parm = new API_Params_Mainmodule ( $this->hooks, $info,
			$this->parm->settings );
		$this->set_params ( $this->params );
	}


	# ----- Setting params ----- #

	public function get_param ( $name )
	{
		return $this->parm->get_param ( $name );
	}

	public function set_param ( $name, $value = "" )
	{
		return $this->parm->set_param ( $name, $value );
	}

	public function clear_param ( $name, $value = NULL )
	{
		return $this->parm->clear_param ( $name, $value );
	}

	public function set_format ( $format )
	{
		return $this->set_param ( 'format', $format );
	}

	public function set_maxlag ( $maxlag )
	{
		return $this->set_param ( 'maxlag', $maxlag );
	}


	public function set_version ( $version )
	{
		return $this->set_param ( 'version', $version );
	}

	public function set_smaxage ( $smaxage )
	{
		return $this->set_param ( 'smaxage', $smaxage );
	}

	public function set_maxage ( $maxage )
	{
		return $this->set_param ( 'maxage', $maxage );
	}

	public function set_requestid ( $requestid )
	{
		return $this->set_param ( 'requestid', $requestid );
	}

	public function set_servedby ( $servedby )
	{
		return $this->set_param ( 'servedby', $servedby );
	}


}
