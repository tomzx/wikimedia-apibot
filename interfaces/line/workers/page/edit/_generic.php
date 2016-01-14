<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic Edit page Worker class
#  (implements auto-fetch and auto-submit page functionality)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../../fetchers/_auto/page.php' );
require_once ( dirname ( __FILE__ ) . '/../../../writers/wiki/edit.php' );
require_once ( dirname ( __FILE__ ) . '/../../../../../core/data/page.php' );


abstract class Worker_EditPage extends Worker_Page
{

	public $autofetch;

	public $autofetcher_create_missing_pages = false;

	protected $autofetcher_page;


	# ----- Constructor ----- #

	function __construct ( $core, $tasks = array(),
		$autofetch = true, $autosubmit = true,
		$fetch_page_properties = NULL )
	{
		$this->autofetch = $autofetch;

		$this->autofetcher_page = new Autofetcher_Page ( $core,
			"Worker " . $this->signal_log_slot_name(),
			array ( $this, "autofetcher_check" ),
			$fetch_page_properties );

		parent::__construct ( $core, $tasks, $autosubmit );
	}


	# ----- Tools ----- #

	public function autofetcher_check ( $signal )
	{
		$data_block = $signal->data_block ( $this->default_data_key );

		if ( strpos ( $data_block['type'], "/page" ) !== false )
		{

			$result = ( ( is_array ( $data_block['*'] ) &&
				! isset ( $data_block['*']['text'] ) ) ||
				( is_object ( $data_block['*'] ) &&
				! isset ( $data_block['*']->text ) ) );
			if ( $result )
				$this->log ( "Page text is not set - must fetch the page", LL_DEBUG );
			return $result;

		}
		else
		{

			$this->log ( "Data is not a page array or object - must fetch the page",
				LL_DEBUG );
			return true;

		}

	}


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Edit";
	}


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );
		if ( $result === false )
			return false;

		if ( $this->autofetch )
		{
			$this->autofetcher_page->create_missing_pages =
				$this->autofetcher_create_missing_pages;
			if ( ! $this->autofetcher_page->check_and_fetch ( $signal ) )
				return false;
		}

		$data_block = $signal->data_block ( $this->default_data_key );

		if ( $data_block['type'] == "array/page" )
		{
			$data_block['*'] = new Page ( $this->core, $data_block['*'] );
			$data_block['type'] = "object/page";
		}
		if ( $data_block['type'] !== "object/page" )
		{
			$this->log ( "Could not recognize data as a page!", LL_ERROR );
			return false;
		}

		$result = $this->make_edits ( $data_block );
		$signal->set_data_block ( $this->default_data_key, $data_block );

		$this->set_jobdata ( $result );

		return $result;
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'autofetch' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'autofetch' );

		return parent::set_params ( $params );
	}


	# ----- Implementing ----- #


	protected function autosubmitter ( &$signal )
	{
		return new Writer_Wiki_Edit ( $this->core );
	}


	# ----- Abstract ----- #


	abstract protected function make_edits ( &$data_block );


}
