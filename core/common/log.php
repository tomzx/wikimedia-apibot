<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Mains: Log.
#
#  Logging support.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


# ----- Bot loglevels definitions ----- #

define ( 'LL_PANIC'  , 0 );  // typically, the bot is expected to die after this.
define ( 'LL_ERROR'  , 1 );
define ( 'LL_WARNING', 2 );
define ( 'LL_INFO'   , 3 );
define ( 'LL_DEBUG'  , 4 );


class Log
{

	protected $logfile;    // filename to write the log in
	public    $loglevel;   // levels: 0 (panic), 1 (error), 2 (warning), 3 (info), 4 (debug)
	public    $echo_log;   // echo the log on the screen, too
	public    $html_log;   // format the log in HTML
  public    $levelprefs; // line loglevel prefacing chars in the logfile


	# ----- Constructor ----- #

	function __construct ( $params = array() )
	{
		if ( ! isset ( $params['logfile'] ) )
			$params['logfile'] = basename ( $_SERVER['SCRIPT_FILENAME'], '.php' ) . '.log';
		if ( ! isset ( $params['loglevel'] ) )
			$params['loglevel'] = LL_INFO;
		if ( ! isset ( $params['echo_log'] ) )
			$params['echo_log'] = true;
		if ( ! isset ( $params['html_log'] ) )
			$params['html_log'] = false;
		if ( ! isset ( $params['levelprefs'] ) )
			$params['levelprefs'] = array (
				LL_PANIC => '!',
				LL_ERROR => '#',
				LL_WARNING => '=',
				LL_INFO => '+',
				LL_DEBUG => '-'
			);

		$this->logfile    = $params['logfile'];
		$this->loglevel   = $params['loglevel'];
		$this->echo_log   = $params['echo_log'];
		$this->html_log   = $params['html_log'];
		$this->levelprefs = $params['levelprefs'];
	}


	# ----- Tools ----- #

	protected function write_logline ( $line )
	{
		$fp = @fopen ( $this->logfile, 'a+' );
		if ( ! $fp )
			return false;
		flock ( $fp, LOCK_EX );
		$write_result = @fputs ( $fp, $line );
		flock ( $fp, LOCK_UN );
		if ( ! $write_result )
			return false;
		if ( ! @fclose ( $fp ) )
			return false;
		return true;
	}

	public function log ( $msg, $msglevel = LL_INFO, $preface = "" )
	{
		$msg = $preface . $msg;
		if ( $msglevel <= $this->loglevel )
		{
			if ( ! empty ( $msg ) )
			{
				$msg = $this->levelprefs[$msglevel] . ' ['. date('Y-m-d H:i:s') .'] '. $msg;
				if ( $this->echo_log )
				{
					# print errors in red
					echo ( ( $msglevel < LL_WARNING ) ? "\033[31m$msg\033[0m" : $msg ) . "\n";
					flush();
				}
			}
			if ( $this->html_log )
				$msg = "<p>" . $msg . "</p>";
			if ( $this->logfile !== "" )
				$this->write_logline ( $msg . "\n" );
		}
	}


	public function stringify ( $var )
	{
		if ( is_null ( $var ) )
			return "NULL";

		if ( is_numeric ( $var ) )
			return $var;

		if ( is_string ( $var ) )
			return '"' . $var . '"';

		if ( is_bool ( $var ) )
			return ( $var ? "true" : "false" );

		if ( is_array ( $var ) )
		{
			$string = "";
			foreach ( $var as $key => $value )
			{
				if ( ! empty ( $string ) )
					$string .= ", ";
				if ( ! is_numeric ( $key ) )
					$string .= $key . "=";
				$string .= $this->stringify ( $value );
			}
			return "(" . $string . ")";
		}
	}


}
