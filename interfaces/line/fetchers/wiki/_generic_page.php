<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic wiki page fetcher class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


abstract class Fetcher_Wiki_PageGeneric extends Fetcher_Wiki
{

	public $properties;
	public $revid;
	public $section;

	public $create_missing_pages = false;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Page";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'properties' );
		$this->_get_param ( $params, 'revid' );
		$this->_get_param ( $params, 'section' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'properties' );
		$this->_set_param ( $params, 'revid' );
		$this->_set_param ( $params, 'section' );

		return parent::set_params ( $params );
	}


	# ----- Instantiated ----- #

	protected function process_data ( &$signal )
	{
		parent::process_data ( $signal );

		$params = array();
		if ( isset ( $this->properties ) )
			$params['properties'] = $this->properties;
		if ( isset ( $this->revid ) )
			$params['revid'     ] = $this->revid;
		if ( isset ( $this->section ) )
			$params['section'   ] = $this->section;

		$page = $this->fetch_page ( $signal, $params );

		if ( ( $page === false ) &&
			$this->create_missing_pages &&
			! is_null ( $title = $signal->data_title ( $this->default_data_key ) ) )
		{
			$page = new Page ( $this->core, array ( 'title' => $title ) );
			$this->log ( "Creating page [[$title]] object by title only..." );
		}

		$result = $this->set_fetched_data ( $signal, $page );

		$this->set_jobdata ( $result );

		return $result;
	}


	protected function element_typemark ()
	{
		return "page";
	}

	# ----- Abstract ----- #

	abstract protected function fetch_page ( &$signal, &$params );


}
