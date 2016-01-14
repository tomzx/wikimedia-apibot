<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - File (by name) fetcher class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Fetcher_Wiki_Filename extends Fetcher_Wiki
{

	public $prop;

	public $urlwidth;
	public $urlheight;

	public $metadataversion;

	public $extmetadatalanguage;
	public $extmetadatamultilang;
	public $extmetadatafilter;

	public $urlparam;

	public $localonly;


	public $fetch_body = false;

	public $body_file;


	# ----- Overriding ----- #


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'prop' );
		$this->_get_param ( $params, 'urlwidth' );
		$this->_get_param ( $params, 'urlheight' );
		$this->_get_param ( $params, 'metadataversion' );
		$this->_get_param ( $params, 'extmetadatalanguage' );
		$this->_get_param ( $params, 'extmetadatamultilang' );
		$this->_get_param ( $params, 'extmetadatafilter' );
		$this->_get_param ( $params, 'urlparam' );
		$this->_get_param ( $params, 'localonly' );
		$this->_get_param ( $params, 'fetch_body' );
		$this->_get_param ( $params, 'body_file' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'prop' );
		$this->_set_param ( $params, 'urlwidth' );
		$this->_set_param ( $params, 'urlheight' );
		$this->_set_param ( $params, 'metadataversion' );
		$this->_set_param ( $params, 'extmetadatalanguage' );
		$this->_set_param ( $params, 'extmetadatamultilang' );
		$this->_set_param ( $params, 'extmetadatafilter' );
		$this->_set_param ( $params, 'urlparam' );
		$this->_set_param ( $params, 'localonly' );
		$this->_set_param ( $params, 'fetch_body' );
		$this->_set_param ( $params, 'body_file' );

		return parent::set_params ( $params );
	}


	# ----- Instantiated ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".File";
	}


	protected function process_data ( &$signal )
	{
		if ( ! parent::process_data ( $signal ) )
			return false;

		$title = $signal->data_title ( $this->default_data_key );
		if ( is_null ( $title ) )
			return false;

		$namespace = $this->core->info->title_namespace ( $title );
		if ( empty ( $namespace ) )
			$title = $this->core->info->namespace_name ( 'File' ) . ':' . $title;

		$params = array (
			'title' => $title,
			'properties' => array ( 'imageinfo' => array() ),
		);

		$paramnames = $this->core->info->param_querymodule_parameters_names (
			'imageinfo' );
		foreach ( $paramnames as $paramname )
			if ( isset ( $this->$paramname ) && ! is_null ( $this->$paramname ) )
				$params['properties']['imageinfo'][$paramname] = $this->$paramname;

		if ( empty ( $params['properties']['imageinfo']['prop'] ) )
			$params['properties']['imageinfo']['prop'] =
				$this->core->info->param_querymodule_parameter_type (
					'prop', 'imageinfo' );

		require_once ( dirname ( __FILE__ ) .
			'/../../../../core/tasks/fetch_filename.php' );
		$task = new Task_FetchFilename ( $this->core );
		$file = $task->go ( $params );
		if ( $file !== false && $this->fetch_body )
		{
			if ( isset ( $this->body_file ) )
				if ( $this->body_file == "" )
					$this->core->browser->write_into_file = sha1 ( $title );
				else
					$this->core->browser->write_into_file = $this->body_file;

			$browser_max_retries = $this->core->browser->max_retries;
			$this->core->browser->max_retries = 50;

			try {
				$result = $this->core->browser->xfer ( $file->url );
			} catch ( Exception $e ) {
				$result = false;
			}

			if ( $result )
			{
				if ( ! isset ( $this->body_file ) )
				{
					$file->body = $this->core->browser->content;
					$filesize = strlen ( $file->body );
				}
				else
				{
					$file->body_link = $this->core->browser->write_into_file;
					$file->body_link_is_tempfile = true;
					$filesize = filesize ( $file->body_link );
				}

				$this->log ( "Fetched also the file body - $filesize bytes", LL_DEBUG );
			}
			else
			{
				$this->log ( "Tried, but could not fetch the file body!", LL_WARNING );
			}

			$this->core->browser->max_retries = $browser_max_retries;

			unset ( $this->core->browser->write_into_file );
		}

		$result = $this->set_fetched_data ( $signal, $file );

		$this->set_jobdata ( $result, array_merge (
			array ( 'title' => $title, 'fetch_body' => $this->fetch_body ),
			$params['properties']['imageinfo'] )
		);

		return $result;
	}


	protected function element_typemark ()
	{
		return "file";
	}


}
