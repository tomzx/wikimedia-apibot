<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - HTTP-streamed data fetcher class (override on need)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );


class Fetcher_HTTP extends Fetcher
{

	public $uri;
	public $params;
	public $files;
	public $mustbeposted;

	# --- Content type for the data fetched by the xfer --- #
	# --- If not set, will be taken from the xfer Content-Type header received.

	public $content_type;

	public $stop_on_fail = true;


	# ----- Overriding ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".HTTP";
	}


	# ----- Overriding ----- #


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'uri' );
		$this->_get_param ( $params, 'params' );
		$this->_get_param ( $params, 'files' );
		$this->_get_param ( $params, 'mustbeposted' );
		$this->_get_param ( $params, 'content_type' );
		$this->_get_param ( $params, 'stop_on_fail' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'uri' );
		$this->_set_param ( $params, 'params' );
		$this->_set_param ( $params, 'files' );
		$this->_set_param ( $params, 'mustbeposted' );
		$this->_set_param ( $params, 'content_type' );
		$this->_set_param ( $params, 'stop_on_fail' );

		return parent::set_params ( $params );
	}


	# ----- Instantiated ----- #

	protected function process_data ( &$signal )
	{
		parent::process_data ( $signal );

		if ( $this->core->browser->xfer ( $this->uri, $this->params, $this->files,
			$this->mustbeposted ) )
		{
			$data = $this->xferred_data ( $signal );
			$type = $this->content_type ( $signal );

			$result = $this->set_fetched_element ( $signal, $data, $type );
		}
		else
		{
			$result = ( ! $this->stop_on_fail );
		}

		$extra_params = array (
			'uri'          => $uri,
			'params'       => $params,
			'files'        => $files,
			'mustbeposted' => $mustbeposted,
		);
		if ( isset ( $this->content_type ) )
			$extra_params['content_type'] = $this->content_type;

		$this->set_jobdata ( $result, $extra_params, $exclude_params );

		return $result;
	}


	# ----- Overridable ----- #


	protected function uri_data ( &$signal, $key, $param_group, $param_name,
		$override_name )
	{
		if ( isset ( $this->$override_name ) )
			return $this->$override_name;

		if ( isset ( $this->$param_group ) && isset ( $this->$param_name ) )
			return $signal->get_param ( $this->$param_group, $this->$param_name );

		$params = $signal->data_element ( $this->default_data_key );

		if ( is_null ( $key ) )
			$params = array();
		else
			if ( is_object ( $params ) )
				$params = $params->$key;
			elseif ( is_array ( $params ) )
				$params = $params[$key];
			else
				$params = NULL;

		if ( is_array ( $params ) )
			return $params;
		elseif ( is_string ( $params ) )
			return array ( $key => $params );
		else
			return NULL;
	}


	protected function uri_string ( &$signal )
	{
		return $this->uri_data ( $signal, $this->uri_key,
			"uri_param_group", "uri_param_name", "uri" );
	}


	protected function uri_params ( &$signal )
	{
		return $this->uri_data ( $signal, $this->params_key,
			"params_param_group", "params_param_name", "params" );
	}


	protected function uri_files ( &$signal )
	{
		return $this->uri_data ( $signal, $this->files_key,
			"files_param_group", "files_param_name", "files" );
	}


	protected function uri_mustbeposted ( &$signal )
	{
		return $this->uri_data ( $signal, $this->mustbeposted_key,
			"mustbeposted_param_group", "mustbeposted_param_name", "mustbeposted" );
	}


	protected function xferred_data ( &$signal )
	{
		return $this->core->browser->content;
	}


	protected function content_type ( &$signal )
	{
		if ( isset ( $this->content_type ) )
			return $this->content_type;
		else
			return $this->core->browser->find_header ( "Content-Type" );
	}


}
