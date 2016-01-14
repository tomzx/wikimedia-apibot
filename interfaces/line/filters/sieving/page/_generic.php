<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic Page Filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../../fetchers/_auto/page.php' );



abstract class Filter_Page extends Filter_Sieving
{

	protected $page_autofetcher;
	protected $data_property = "title";


	# ----- Constructor ----- #


	function __construct ( $core, $checker_params = NULL,
		$fetch_page_properties = NULL )
	{
		parent::__construct ( $core, $checker_params );

		$this->page_autofetcher = new Autofetcher_Page ( $core,
			"Filter " . $this->signal_log_slot_name(),
			array ( $this, "autofetcher_check" ),
			$fetch_page_properties );
	}


	# ----- Tools ----- #


	public function autofetcher_check ( $signal )
	{
		$data_type = $signal->data_type ( $this->default_data_key );
		$result = ( strpos ( $data_type, "/page" ) === false );
		if ( $result )
		{
			$this->log ( "Element is not a page array or object - must fetch the page",
				LL_DEBUG );
		}
		else
		{
			$result = is_null ( $this->element_to_check ( $signal ) );
			if ( $result )
				$this->log ( "Page property '" . $this->data_property .
					"' is not set - must (re-)fetch the page", LL_DEBUG );
		}
		return $result;
	}


	# ----- Instantiating ----- #


	protected function element_id_string ( &$signal )
	{
		$data = parent::element_to_check ( $signal );

		if ( is_array ( $data ) && isset ( $data['title'] ) )
			$title = $data['title'];
		elseif ( is_object ( $data ) && isset ( $data->title ) )
			$title = $data->title;
		elseif ( is_string ( $data ) )
			$title = $data;
		else
			$title = "---unknown title (something must be wrong!)---";

		return "page [[" . $title . "]]";
	}


	protected function slotname_preface ()
	{
		return "Page";
	}


	# ----- Access point ----- #


	public function process_data ( &$signal )
	{
		if ( $this->page_autofetcher->check_and_fetch ( $signal ) )
			return parent::process_data ( $signal );
		else
			return NULL;
	}


}
