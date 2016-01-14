<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web interface basic data exchange class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../../_generic/exchanger.php' );


class ApibotException_MWWebError extends ApibotException_AccessRetry
{
	function __construct ( $error )
	{
		parent::__construct (
			"mwweberror",
			"MediaWiki Web error: " . $error
		);
	}
}




class Exchanger_Web extends Exchanger
{


	# ----- Overriding ----- #

	public function nohooks__xfer ( $hook_object,
		$vars, $files = array(), $mustbeposted = false )
	{
		if ( ! $mustbeposted )
			return parent::xfer ( $vars, $files, $mustbeposted );

		$temp_url = $this->url;
		$url_params = array();

		if ( isset ( $vars['title'] ) )
		{
			$url_params['title'] = 'title=' .
				urlencode ( str_replace ( ' ', '_', $vars['title'] ) );
			unset ( $vars['title'] );
		}

		if ( isset ( $vars['action'] ) )
		{
			$url_params['action'] = 'action=' . urlencode ( $vars['action'] );
			unset ( $vars['action'] );
		}

		$url_paramstring = implode ( '&', $url_params );
		if ( ! empty ( $url_paramstring ) )
			$this->url .= "?" . $url_paramstring;

		$result = parent::xfer ( $vars, $files, $mustbeposted );

		$this->url = $temp_url;

		return $result;
	}


	# ----- Implemented ----- #

	public function nohooks__get_params ( $hook_object )
	{
		return array();
	}

	public function nohooks__set_params ( $hook_object, $params )
	{
		// dummy - the module currently uses no params.
	}

	public function nohooks__clear_params ( $hook_object )
	{
		// dummy - the module currently uses no params.
	}


	public function nohooks__process_reply ( $hook_object )
	{
		if ( strpos ( $this->browser->find_header ( 'Content-Type' ), 'text/html' )
			=== false )
		{
			return new ApibotException_BadContentType();
		}
		elseif ( strripos ( $this->browser->content, '</html>' ) === false )
		{
			return new ApibotException_MWWebError ( "Partial or malformed HTML" );
		}
		else
		{
			$this->data = $this->browser->content;  // bears some tidying - todo!
			return true;
		}
	}


	public function set_info ( &$info )
	{
		// dummy, by now
	}


}
