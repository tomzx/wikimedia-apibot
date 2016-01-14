<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic File Filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../../fetchers/_auto/file.php' );



abstract class Filter_File extends Filter_Sieving
{


	protected $file_autofetcher;
	protected $data_property = "title";


	# ----- Constructor ----- #


	function __construct ( $core, $checker_params = NULL,
		$fetch_page_properties = NULL )
	{
		if ( is_null ( $fetch_page_properties ) )
			$fetch_file_properties = array (
				'imageinfo' => array (
					'prop' => $core->info->param_querymodule_parameter_type (
						'prop', 'imageinfo'
					),
				),
			);

		parent::__construct ( $core, $checker_params );

		$this->file_autofetcher = new Autofetcher_File ( $core,
			"Filter " . $this->signal_log_slot_name(),
			array ( $this, "autofetcher_check" ),
			$fetch_file_properties );
	}


	# ----- Tools ----- #


	public function autofetcher_check ( $signal )
	{
		$data_type = $signal->data_type ( $this->default_data_key );
		$result = ( strpos ( $data_type, "/file" ) !== false );
		if ( $result )
		{
			$this->log ( "Element is not an file array or object - must fetch the file",
				LL_DEBUG );
		}
		else
		{
			$result = is_null ( $this->element_to_check ( $signal ) );
			if ( $result )
				$this->log ( "File property '" . $this->data_property .
					"' is not set - must (re-)fetch the file", LL_DEBUG );
		}
		return $result;
	}


	# ----- Instantiating ----- #


	protected function element_id_string ( &$signal )
	{
		$data = $signal->data_element ( $this->default_data_key );

		if ( is_array ( $data ) && isset ( $data['title'] ) )
			$title = $data['title'];
		elseif ( is_object ( $data ) && isset ( $data->title ) )
			$title = $data->title;
		elseif ( is_string ( $data ) )
			$title = $data;
		else
			$title = "0:---unknown filename (something must be wrong!)---";  // 0: is a fake namespace to be removed below

		$filename = $this->core->info->title_name ( $title );

		return "'$filename'";
	}


	protected function slotname_preface ()
	{
		return "File";
	}


	# ----- Access point ----- #


	public function process_data ( &$signal )
	{
		if ( $this->file_autofetcher->check_and_fetch ( $signal ) )
			return parent::process_data ( $signal );
		else
			return NULL;
	}


}
