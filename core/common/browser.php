<?php
#
#  Browser class
#
#  Copyright (C) 2004 Borislav Manolov
#
#  This program is in the public domain.
#
#  Author: Borislav Manolov <b.manolov at gmail dot com>
#          http://purl.org/NET/borislav/
#
#  This program uses portions of
#    Snoopy - the PHP net client
#    Author: Monte Ohrt <monte@ispi.net>
#    Copyright (c): 1999-2000 ispi, all rights reserved
#    Version: 1.01
#    http://snoopy.sourceforge.net/
#
#  Modified by Grigor Gatchev <grigor at gatchev dot info>, 2007-2014.
#
# ---------------------------------------------------------------------------- #



require_once ( dirname ( __FILE__ )  . '/exceptions.php' );



# ---------------------------------------------------------------------------- #
# --                              Exceptions                                -- #
# ---------------------------------------------------------------------------- #


class ApibotException_BrowserError extends ApibotException_AccessClose
{
	function __construct ( $error = "Unknown browser error" )
	{
		parent::__construct (
			"browsererror",
			$error
		);
	}
}

class ApibotException_BadURL extends ApibotException_AccessClose
{
	function __construct ( $url )
	{
		parent::__construct (
			"badurl",
			"Bad URL: " . $url
		);
	}
}

class ApibotException_InvalidProto extends ApibotException_AccessClose
{
	function __construct ( $proto )
	{
		parent::__construct (
			"invalidproto",
			"Invalid network protocol: " . $proto
		);
	}
}

class ApibotException_CantDoHttps extends ApibotException_AccessClose
{
	function __construct ()
	{
		parent::__construct (
			"cantdohttps",
			"No SSL support - cannot make HTTPS requests"
		);
	}
}

class ApibotException_BrokenConnection extends ApibotException_AccessRetry
{
	function __construct ()
	{
		parent::__construct (
			"brokenconn",
			"The connection could not be established, or was broken"
		);
	}
}

class ApibotException_DecompFailed extends ApibotException_AccessRetry
{
	function __construct ()
	{
		parent::__construct (
			"decompfailed",
			"Data decompression failed"
		);
	}
}

class ApibotException_HTTP404 extends ApibotException_AccessClose
{
	function __construct ()
	{
		parent::__construct (
			"httperror404",
			"HTTP error 404: File not found"
		);
	}
}



# ---------------------------------------------------------------------------- #
# --                               Browser                                  -- #
# ---------------------------------------------------------------------------- #


class Browser
{

	# --- Settings --- #

	public $agent         = "Mozilla/5.0 (Apibot Browser)";

	public $http_version  = "HTTP/1.1";

	public $http_user     = NULL;   // for HTTP password protection
	public $http_pass     = NULL;

	public $conn_timeout  = 120;  // connection opening timeout

	public $max_retries   = 1;

	public $max_get_len   = 2048;

	public $content_type  = array (
		'text'   => "application/x-www-form-urlencoded",
		'binary' => "multipart/form-data",
	);

	public $mime_boundary = "Apibot-Browser-$1";

	public $use_compression = true;   // if true, tells the site gzip-compressed data is accepted

	public $use_persistent_connections = true;  // will try to...

	public $write_into_file;


	public $speed_limits  = array();  // will not exceed these, if set

	public $dump_level = 0;


	public $data_portion_length = 500000;  // read non-chunked large data in such portions

	# --- Work info --- #

	public $last_postdata_size;    // size in bytes of the last postdata sent
	public $last_times = array();  // timestamps of the last request start
	public $last_compressed;       // data received were compressed by this method (NULL - none)

	public $headers = array();     // received headers
	public $cookies = array();     // received cookies

	public $content;               // content returned from server

	public $bytecounters = array ( // how much data were transferred
		'total' => array (
			'DL' => array ( 'c' => 0, 'u' => 0 ),
			'UL' => array ( 'c' => 0, 'u' => 0 ),
		),
		'last' => array (
			'DL' => array ( 'c' => 0, 'u' => 0 ),
			'UL' => array ( 'c' => 0, 'u' => 0 ),
		),
	);


	# --- Internal values --- #


	private $uri_parts;

	private $connection_target;

	private $connections_fps = array();

	private $write_into_file_fp;


	# ----- Constructor ----- #

	function __construct ( $params = array() )
	{
		if ( is_null ( $params ) )
			$params = array();

		foreach ( $params as $key => $value )
		{
			$this->$key = $value;
		}

		$randstr = sha1 ( time() );
		$this->mime_boundary = str_replace ( '$1', $randstr, $this->mime_boundary );

		if ( ! is_array ( $this->speed_limits ) )
		{
			$this->speed_limits = array (
				'total' => $this->speed_limits,
				'DL' => NULL,
				'UL' => NULL,
			);
		}
	}


	function __destruct ()
	{
		$this->close_data_connection();
	}


	# ----- Service functions ----- #


	public function flush ()
	{
		$this->content = NULL;
	}


	public function last_time ()
	{
		return ( $this->last_times['beg'] + $this->last_times['closed'] ) / 2;
	}


	# ----- Bytecounters ----- #


	public function reset_bytecounters ()
	{
		$bytecounters = $this->bytecounters;
		$this->bytecounters = array (
			'total' => array (
				'DL' => array ( 'c' => 0, 'u' => 0 ),
				'UL' => array ( 'c' => 0, 'u' => 0 ),
			),
			'last' => array (
				'DL' => array ( 'c' => 0, 'u' => 0 ),
				'UL' => array ( 'c' => 0, 'u' => 0 ),
			),
		);
		return $bytecounters;
	}

	protected function add_bytecounters_dl ( $compressed, $uncompressed )
	{
		$this->bytecounters['total']['DL']['c'] += $compressed;
		$this->bytecounters['last' ]['DL']['c']  = $compressed;
		$this->bytecounters['total']['DL']['u'] += $uncompressed;
		$this->bytecounters['last' ]['DL']['u']  = $uncompressed;
	}

	protected function add_bytecounters_ul ( $compressed, $uncompressed )
	{
		$this->bytecounters['total']['UL']['c'] += $compressed;
		$this->bytecounters['last' ]['UL']['c']  = $compressed;
		$this->bytecounters['total']['UL']['u'] += $uncompressed;
		$this->bytecounters['last' ]['UL']['u']  = $uncompressed;
	}


	# ----- Headers support ----- #

	public function find_header ( $name )
	{
		foreach ( $this->headers as $header )
		{
			if ( $header['name'] == $name )
				return $header['value'];
		}
		return false;
	}

	public function match_header ( $regex )
	{
		foreach ( $this->headers as $header )
		{
			if ( preg_match ( $regex, $header['name']  ) )
				return $header;
		}
		return false;
	}


	# ----- Cookies support ----- #

	# --- Cookies <--> HTTP headers --- #

	private function set_cookies_by_headers ()
	{
		foreach ( $this->headers as $header )
		{
			if ( $header['name'] == "Set-Cookie" )
			{
				preg_match ( '/([^=]+)=([^;]+);/i', $header['value'], $matches );
				$cookie_name  = $matches[1];
				$cookie_value = $matches[2];
				if ( preg_match ( '/expires=([^;$]+)/i', $header['value'], $matches ) )
					$exp_time = $matches[1];
				else
					$exp_time = time() + 60*60*24*30;
				$this->cookies[$cookie_name] = array (
					'content' => $cookie_value,
					'exp' => $exp_time,
				);
			}
		}
	}

	private function cookies_header ()
	{
		if ( empty ( $this->cookies ) )
		{
			return '';
		}
		else
		{
			$cookie_strings = array();
			foreach ( $this->cookies as $cookie_name => $cookie_data )
				if ( time() <= $cookie_data['exp'] )
					$cookie_strings[] = $cookie_name .'='. $cookie_data['content'];
			return 'Cookie: ' . implode ( '; ', $cookie_strings ) . "\r\n";
		}
	}

	# --- Host cookies management --- #

	public function get_cookie ( $cookie_name )
	{
		if ( isset ( $this->cookies[$cookie_name] ) )
			return $this->cookies[$cookie_name];
		else
			return false;
	}

	public function set_cookie ( $cookie_name, $cookie )
	{
		if ( ! is_array ( $cookie ) )
			$cookie = array ( 'content' => $cookie );
		$old_cookie = ( isset ( $this->cookies[$cookie_name] )
			? $this->cookies[$cookie_name]
			: NULL );
		$this->cookies[$cookie_name] = $cookie;
		return $old_cookie;
	}

	public function del_cookie ( $cookie_name )
	{
		$old_cookie = $this->cookies[$cookie_name];
		unset ( $this->cookies[$cookie_name] );
		return $old_cookie;
	}

	public function set_cookie_expiration ( $cookie_name, $secs )
	{
		if ( array_key_exists ( $cookie_name, $this->cookies ) )
		{
			$old_exp = $this->cookies[$cookie_name]['exp'];
			$this->cookies[$cookie_name]['exp'] = $secs;
		}
		return $old_exp;
	}

	public function modify_cookie_expiration ( $cookie_name, $secs_diff )
	{
		if ( array_key_exists ( $cookie_name, $this->cookies ) &&
			array_key_exists ( 'exp', $this->cookies[$cookie_name] ) )
		{
			$old_exp = $this->cookies[$cookie_name]['exp'];
			$this->cookies[$cookie_name]['exp'] += $secs_diff;
		}
		return $old_exp;
	}


	# ----- HTTP request servicing ----- #


	private function delay_if_needed ()
	{
		if ( empty ( $this->speed_limits ) || empty ( $this->last_times ) )
			return;
		if ( isset ( $this->speed_limits['total'] ) && ! empty ( $this->speed_limits['total'] ) )
			$secs_total = ( $this->bytecounters['last']['DL']['c'] +
			                $this->bytecounters['last']['UL']['c'] ) /
				$this->speed_limits['total'];
		if ( isset ( $this->speed_limits['DL'] ) && ! empty ( $this->speed_limits['DL'] ) )
			$secs_DL = $this->bytecounters['last']['DL']['c'] / $this->speed_limits['DL'];
		if ( isset ( $this->speed_limits['UL'] ) && ! empty ( $this->speed_limits['UL'] ) )
			$secs_UL = $this->bytecounters['last']['UL']['c'] / $this->speed_limits['UL'];
		$wait_secs = max ( $secs_total, $secs_DL, $secs_UL ) + $this->last_times['beg'] - time();
		if ( $wait_secs > 0 )
			@sleep ( $wait_secs );
	}


	private function dump ( $string )
	{
		if ( $this->dump_level > 0 )
			echo $string;
	}


	private function dump_data ( $label, $data )
	{
		if ( $this->dump_level > 0 )
		{
			echo $label;
			var_dump ( $data );
			echo "\n";
		}
	}


	# ----- HTTP request data strings ----- #


	private function get_string ( $vars )
	{
		$string = '';
		foreach ( $vars as $key => $val )
		{
			if ( ! empty ( $string ) )
				$string .= '&';
			if ( is_array ( $val ) )
			{
				foreach ( $val as $sub )
				{
					$string .= urlencode ( $key . "[]" ) . '=' . urlencode ( $sub );
				}
			}
			else
			{
				$string .= urlencode ( $key ) . '=' . urlencode ( $val );
			}
		}
		return $string;
	}


	private function post_string ( $vars, $files )
	{
		$string = '';

		if ( is_array ( $vars ) )
			foreach ( $vars as $key => $val )
			{
				$string .= '--'. $this->mime_boundary ."\r\n";
				$string .= 'Content-Disposition: form-data; name="'. $key ."\"\r\n\r\n";
				$string .= $val . "\r\n";
			}

		if ( is_array ( $files ) && ! empty ( $files ) )
		{
			list ( $field_name, $file_name ) = each ( $files );
			if ( ! is_readable ( $file_name ) )
				throw new ApibotException_CantReadFile ( $file_name );

			$fp = fopen ( $file_name, 'r' );
			$file_content = fread ( $fp, filesize ( $file_name ) );
			fclose ( $fp );
			$base_name = basename ( $file_name );

			$string .= '--' . $this->mime_boundary . "\r\n";
			$string .= 'Content-Disposition: form-data; name="'. $field_name .
				'"; filename="' . $base_name . "\"\r\n\r\n";
			$string .= $file_content . "\r\n";
			$string .= '--' . $this->mime_boundary . "--\r\n";
		}

		return $string;
	}


	private function start_data_receiving ()
	{
		if ( isset ( $this->write_into_file ) )
		{
			$this->write_into_file_fp = @fopen ( $this->write_into_file, 'w+' );
		}
		else
		{
			$this->content = "";
		}
	}


	private function append_received_portion ( $portion )
	{
		if ( isset ( $this->write_into_file ) )
		{
			@fwrite ( $this->write_into_file_fp, $portion );
		}
		else
		{
			$this->content .= $portion;
		}
	}


	private function end_data_receiving ()
	{
		if ( isset ( $this->write_into_file ) )
		{
			@fclose ( $this->write_into_file_fp );
		}
	}


	private function add_bytecounters_compressed_uncompressed ()
	{
		if ( $this->find_header ( 'Content-Encoding' ) == "gzip" )
		{
			if ( isset ( $this->write_into_file ) )
			{
				$compressed_data_len = filesize ( $this->write_into_file );
				$this->gzinflate_file ( $this->write_into_file );
				$uncompressed_data_len = filesize ( $this->write_into_file );
			}
			else
			{
				$compressed_data_len = strlen ( $this->content );
				$this->content = gzinflate ( substr ( $this->content, 10 ) );
					// the 10 bytes stripped are the "member header" of the gzip compression;
					// gzdecode() should be cleaner, but is still unavailable in PHP 5.1
			}
			$this->last_compressed = "gzip";
			if ( $this->dump_level > 0 )
				echo "Uncompressed data: " . $this->content . "\n";
			$uncompressed_data_len = strlen ( $this->content );
		}
		else
		{
			if ( isset ( $this->write_into_file ) )
			{
				$compressed_data_len = filesize ( $this->write_into_file );
				$uncompressed_data_len = filesize ( $this->write_into_file );
			}
			else
			{
				$compressed_data_len = strlen ( $this->content );
				$uncompressed_data_len = strlen ( $this->content );
				$this->last_compressed = NULL;
			}
		}

		$this->add_bytecounters_dl ( $compressed_data_len, $uncompressed_data_len );
	}


	private function gzinflate_file ( $compressed_filename )
	{
		$uncompressed_filename = dirname ( $compressed_filename ) . sha1 ( time() );

		$zp = gzopen ( $compressed_filename, 'r' );
		$fp = fopen ( $uncompressed_filename, 'w+' );

		while ( $data = gzread ( $zp, $this->data_portion_length ) )
			@fwrite ( $fp, $data );

		fclose ( $fp );
		gzclose ( $zp );

		unlink ( $compressed_filename );
		rename ( $uncompressed_filename, $compressed_filename );
	}


	private function open_data_connection ( $uri )
	{
		$this->uri_parts = parse_url ( $uri );

		if ( empty ( $this->uri_parts['scheme'] ) )  // parse_url() failed
			throw new ApibotException_BadURL ( $uri );

		switch ( $this->uri_parts['scheme'] )
		{
			case 'http'  :
				$this->uri_parts['prefix'] = '';
				if ( empty ( $this->uri_parts['port'] ) )
					$this->uri_parts['port'] = 80;
				break;

			case 'https' :
				if ( in_array ( 'openssl', get_loaded_extensions() ) )
				{
					$this->uri_parts['prefix'] = 'ssl://';
					if ( empty ( $this->uri_parts['port'] ) )
						$this->uri_parts['port'] = 443;
					break;
				}
				else
				{
					throw new ApibotException_CantDoHttps();
				}

			default :
				throw new ApibotException_InvalidProto ( $this->uri_parts['scheme'] );
		}

		$this->connection_target = $this->uri_parts['prefix'] .
			$this->uri_parts['host'] . ":" . $this->uri_parts['port'];
		if ( isset ( $this->connections_fps[$this->connection_target] ) )
			return true;

		$this->last_times = array ('beg' => microtime ( true ) );

		if ( ! ( $this->connections_fps[$this->connection_target] =
			@fsockopen ( $this->uri_parts['prefix'] . $this->uri_parts['host'],
			$this->uri_parts['port'], $errno, $errstr, $this->conn_timeout ) ) )
		{
			unset ( $this->connections_fps[$this->connection_target] );
			throw new ApibotException_BrokenConnection();
		}
		$this->last_times['open'] = microtime ( true );
	}


	private function close_data_connection ()
	{
		if ( isset ( $this->connections_fps[$this->connection_target] ) )
		{
			if ( fclose ( $this->connections_fps[$this->connection_target] ) === false )
				throw new ApibotException_BrokenConnection();
			unset ( $this->connections_fps[$this->connection_target] );
		}
	}


	private function receive_string_data ( $dump_label )
	{
		$this->dump ( "Receiving $dump_label... " );

		if ( ( $result = fgets ( $this->connections_fps[$this->connection_target] ) ) === false )
		{
			$this->close_data_connection();
			throw new ApibotException_BrokenConnection();
		}

		$this->dump_data ( "Received: ", $result );

		return $result;
	}


	private function receive_data ( $dump_label, $length )
	{
		$this->dump ( "Receiving $dump_label, length $length... " );

		$result = "";

		while ( $length )
		{
			if ( ( $piece = fread ( $this->connections_fps[$this->connection_target], $length ) ) === false )
			{
				$this->close_data_connection();
				throw new ApibotException_BrokenConnection();
			}

			$result .= $piece;
			$length -= strlen ( $piece );
		}

		$this->dump_data ( "Received: ", $result );

		return $result;
	}


	private function send_data ( $dump_label, $data )
	{
		$this->dump_data ( "Sending $dump_label: ", $data );

		$this->dump_data ( $dump_label, $data );

		if ( ( ( $result = fwrite ( $this->connections_fps[$this->connection_target], $data ) ) === false ) ||
			( $result < strlen ( $data ) ) )
		{
			$this->close_data_connection();
			throw new ApibotException_BrokenConnection();
		}

		$this->dump ( "Sent!\n" );

		return $result;
	}


	protected function http ( $uri, $request_method, $content_type, $postdata )
	{
		$this->delay_if_needed();

		$this->last_postdata_size = strlen ( $postdata );

		$this->open_data_connection ( $uri );

		# --- data sending start --- #

		if ( empty ( $this->uri_parts['path'] ) )
			$this->uri_parts['path'] = '/';

		if ( $request_method == 'GET' )
		{
			if ( ! empty ( $this->uri_parts['query'] ) )
				$postdata = $this->uri_parts['query'] .
					( empty ( $postdata ) ? "" : '&' . $postdata );
			if ( ! empty ( $postdata ) )
			{
				$this->uri_parts['path'] .= '?' . $postdata;
				$postdata = "";
			}
		}
		else
		{
			if ( ! empty ( $this->uri_parts['query'] ) )
				$this->uri_parts['path'] .= '?' . $this->uri_parts['query'];
		}

		$headers = $request_method . " " . $this->uri_parts['path'] . " " .
			$this->http_version. "\r\n" .
			"User-Agent: " . $this->agent . "\r\n" .
			"Host: " . $this->uri_parts['host'] . "\r\n" .
			"Accept: */*\r\n";

		if ( $this->use_compression && function_exists ( "gzinflate" ) )
			$headers .= "Accept-Encoding: gzip\r\n";

		if ( $this->use_persistent_connections )
			$headers .= "Connection: Keep-Alive\r\n";

		if ( ! empty ( $this->http_user ) )
			$headers .= 'Authorization: Basic ' .
				base64_encode ( $this->http_user . ':' . $this->http_pass ) . "\r\n";

		$headers .= $this->cookies_header ( $this->cookies );

		if ( ! empty ( $content_type ) )
		{
			$headers .= "Content-type: " . $content_type;
			if ( $content_type == $this->content_type['binary'] )
				$headers .= '; boundary=' . $this->mime_boundary;
			$headers .= "\r\n";
		}

		if ( ! empty ( $postdata ) )
			$headers .= "Content-length: ". strlen ( $postdata ) ."\r\n";
		$headers .= "\r\n";

		$this->send_data ( "headers", $headers );

		$this->last_times['sent_headers'] = microtime ( true );
		$datalen = strlen ( $headers );

		$this->send_data ( "data", $postdata );

		$this->last_times['sent_data'] = microtime ( true );
		$datalen += strlen ( $postdata );

		$this->add_bytecounters_ul ( $datalen, $datalen );

		# --- data sending end; data receiving start --- #

		$this->headers = array();

		$headers_len = 0;
		while ( $curr_header = $this->receive_string_data ( "header" ) )
		{
			if ( $curr_header == "\r\n" )
				break;
			$header_parts = explode ( ':', $curr_header, 2 );
			if ( ( $header_parts[0] == "Location" ) || ( $header_parts[0] == "URI" ) )
				$redirect_to = trim ( $header_parts[1] );
			if ( ! isset ( $header_parts[1] ) )
				$header_parts[1] = NULL;
			$this->headers[] = array (
				'name'  => trim ( $header_parts[0] ),
				'value' => trim ( $header_parts[1] ) );
			$headers_len += strlen ( $curr_header );
		}
		$this->add_bytecounters_dl ( $headers_len, $headers_len );
		$this->last_times['got_headers'] = microtime ( true );

		$this->start_data_receiving();

		if ( $this->find_header ( 'Transfer-Encoding' ) == "chunked" )
		{
			while ( true )
			{
				$chunk_size = $this->receive_string_data ( "chunk size" );
				$chunk_size_dec = hexdec ( $chunk_size );
				if ( $chunk_size_dec == 0 )
				{
					$this->receive_string_data ( "chunks end" );
					break;
				}
				else
				{
					$chunk = '';
					while ( $chunk_size_dec - strlen ( $chunk ) > 0 ) {
						$chunk_piece = $this->receive_data (
							"chunk piece", $chunk_size_dec - strlen ( $chunk ) );
						$chunk .= $chunk_piece;
					}
					$this->receive_string_data ( "chunk" );
				}
				$this->append_received_portion ( $chunk );
			}

		}
		elseif ( ( $content_length = $this->find_header ( 'Content-Length' ) ) !== false )
		{

			do
			{
				$portion_length = ( ( $content_length < $this->data_portion_length )
					? $content_length
					: $this->data_portion_length );
				$content_length -= $portion_length;

				$data = $this->receive_data ( "data", $portion_length );
				$this->append_received_portion ( $data );
			}
			while ( $content_length > 0 );

		}
		else  // server said neither chunked transfer nor content length?! should not exist... fallbacking to braindead read
		{

			do
			{
				$data = $this->receive_data ( "data", $this->data_portion_length );
				$this->append_received_portion ( $data );
			}
			while ( strlen ( $data ) == $this->data_portion_length );

		}

		$this->end_data_receiving();

		$this->last_times['got_data'] = microtime ( true );

		if ( ( $this->find_header ( 'Connection' ) == "close" ) ||
			! $this->use_persistent_connections )
			$this->close_data_connection();

		$this->last_times['closed'] = microtime ( true );

		if ( ( $this->dump_level > 0 ) && isset ( $this->write_into_file ) )
			echo "Received data is written in the file " . $this->write_into_file .
				" (" . filesize ( $this->write_into_file ) . " bytes)\n";

		# --- data receiving end --- #

		if ( isset ( $redirect_to ) )
		{
			$this->http ( $redirect_to, $request_method, $content_type, $postdata );
		}
		else
		{

			$this->add_bytecounters_compressed_uncompressed();

			if ( $this->content === false )
				throw new ApibotException_DecompFailed();

			$this->set_cookies_by_headers();

			if ( $this->match_header ( '/HTTP\/\d+\.\d+ 404 /u' ) )
			{
				if ( isset ( $this->write_into_file ) )
					@unlink ( $this->write_into_file );

				throw new ApibotException_HTTP404();
			}

		}

		return true;
	}


	public function xfer ( $uri, $vars = array(), $files = array(),
		$mustbeposted = false )
	{
		if ( $mustbeposted === false )
		{
			ksort ( $vars );
			$query = $this->get_string ( $vars );
			if ( strlen ( $uri . '?' . $query ) <= $this->max_get_len )
			{
				return $this->http ( $uri, 'GET', NULL, $query );
			}
		}

		$postdata = $this->post_string ( $vars, $files );

		$retries_counter = 0;
		while ( $retries_counter < $this->max_retries )
		{
			if ( $this->http ( $uri, 'POST', $this->content_type['binary'], $postdata ) )
				return true;
			$retries_counter++;
			sleep ( $retries_counter * 5 );
		}

		return false;
	}


}
