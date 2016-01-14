<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backends: Generic: Task.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/backend.php' );
require_once ( dirname ( __FILE__ ) . '/../../data/_generic.php' );



abstract class Backend_Task
{

	protected $backend;
	protected $settings;

	protected $logpreface;


	# ----- Constructor ----- #


	function __construct ( $backend, $settings = array(), $logpreface = "" )
	{
		$this->backend = $backend;
		$this->settings = $settings;
		$this->logpreface = $logpreface;
	}


	# ----- Basic tools ----- #


	public function log ( $message, $level = LL_INFO )
	{
		return $this->backend->log ( $message, $level, $this->logpreface );
	}


	public function is_operable ()
	{
		return $this->backend->is_operable();
	}


	protected function check_result ( $action, $logbeg, $actdesc )
	{
		return true;
	}


	private function keyword_to_string ( $keyword, $value )
	{
		$string = "";
		if ( ! empty ( $keyword ) )
			$string .= $keyword . ": ";

		if ( is_array ( $value ) )
		{

			if ( isset ( $value['error'] ) )
				$string = $keyword . ": " . "Error: " . $value['error']['info'];
			else
			{
				$string_array = array();
				foreach ( $value as $key => $member )
					$string_array[] = $this->keyword_to_string ( $key, $member );
				$string .= " (" . implode ( ", ", $string_array ) . ")";
			}

		}
		else
		{

			if ( ! empty ( $value ) )
			{
				if ( $keyword == "title" )
					$value = '[[' . $value . ']]';
				$string .= $value;
			}

		}

		return $string;
	}


	protected function act_and_log ( $logbeg, $actdesc,
		$params = array(), $setnames = array() )
	{
		$action = $this->action();
		if ( is_null ( $action ) )
			return NULL;

		$action->setnames = $setnames;
		$action->set_params ( $params );

		try
		{
			if ( $action->xfer() )
			{
				if ( $this->check_result ( $action, $logbeg, $actdesc ) )
				{

					$this->log ( $logbeg . " was " . $actdesc . "." );
					if ( $action->mustbeposted() ) // has debug-style output
					{
						$data = $action->results();

						if ( is_array ( $data ) )
							$this->log ( $this->keyword_to_string ( NULL, $data ), LL_DEBUG );
					}

					return $action->results();
				}
			}
		}
		catch ( Exception $e )
		{
			$this->log ( "Error: " . $e->code . " (" . $e->info . ")", LL_ERROR );
		}

		$this->log ( $logbeg . " was NOT " . $actdesc . "." );

		return false;
	}


	# ----- Resolving parameters ----- #


	protected function resolve_param ( $struct, $param_name )
	{
		if ( is_object ( $struct ) && isset ( $struct->$param_name ) )
			return $struct->$param_name;
		elseif ( is_array ( $struct ) && isset ( $struct[$param_name] ) )
			return $struct[$param_name];

		return $struct;
	}


	protected function resolve_string_param ( $struct, $param_name )
	{
		$result = $this->resolve_param ( $struct, $param_name );
		if ( is_string ( $result ) )
			return $result;
		else
			return NULL;
	}


	protected function resolve_nonempty_string_param ( $struct, $param_name )
	{
		$result = $this->resolve_string_param ( $struct, $param_name );
		if ( empty ( $result ) )
			return NULL;
		else
			return $result;
	}


	protected function resolve_numeric_param ( $struct, $param_name )
	{
		$result = $this->resolve_param ( $struct, $param_name );
		if ( is_numeric ( $result ) )
			return $result;
		else
			return NULL;
	}


	protected function resolve_bool_param ( $struct, $param_name )
	{
		$result = $this->resolve_param ( $struct, $param_name );
		if ( is_bool ( $result ) )
			return $result;
		else
			return NULL;
	}


	protected function resolve_page_title ( $struct, $param_name = NULL )
	{
		if ( $param_name === NULL )
			$param_name = 'title';

		$result = $this->resolve_nonempty_string_param ( $struct, $param_name );
		if ( $result === NULL )
			$this->log ( "Cannot determine page title", LL_ERROR );

		return $result;
	}


	protected function resolve_pageid ( $struct, $param_name = NULL )
	{
		if ( $param_name === NULL )
			$param_name = 'pageid';

		$result = $this->resolve_numeric_param ( $struct, $param_name );
		if ( $result === NULL )
			$this->log ( "Cannot determine pageid", LL_ERROR );

		return $result;
	}


	protected function resolve_revid ( $struct, $param_name = NULL )
	{
		if ( $param_name === NULL )
			$param_name = 'revid';

		$result = $this->resolve_numeric_param ( $struct, $param_name );
		if ( $result === NULL )
			$this->log ( "Cannot determine revid", LL_ERROR );

		return $result;
	}


	protected function resolve_user_name ( $struct, $param_name = NULL )
	{
		if ( $param_name === NULL )
			$param_name = 'user';

		$result = $this->resolve_nonempty_string_param ( $struct, $param_name );
		if ( $result === NULL )
			$this->log ( "Cannot determine user name", LL_ERROR );

		return $result;
	}


	protected function resolve_rcid ( $struct, $param_name = NULL )
	{
		if ( $param_name === NULL )
			$param_name = 'rcid';

		$result = $this->resolve_numeric_param ( $struct, $param_name );
		if ( $result === NULL )
			$this->log ( "Cannot determine recentchange ID", LL_ERROR );

		return $result;
	}


	protected function resolve_file_name ( $struct, $param_name = NULL )
	{
		if ( $param_name === NULL )
			$param_name = 'filename';

		$result = $this->resolve_nonempty_string_param ( $struct, $param_name );
		if ( $result === NULL )
			$this->log ( "Cannot determine file name", LL_ERROR );

		return $result;
	}


	# ----- Entry point ----- #


	public function go ( $params )
	{
		return $this->backend->hooks->call (
			get_class ( $this ) . '::go',
			array ( $this, 'nohooks__go' ),
			$this,
			$params
		);
	}


	# ----- Abstract ----- #

	abstract protected function action ();

	abstract public function nohooks__go ( $hook_object, $params );


}
