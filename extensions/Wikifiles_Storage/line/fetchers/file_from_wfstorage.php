<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - File from Wikifiles_Storage space fetcher class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) .
	'/../../../../interfaces/line/fetchers/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../libs/_generic.php' );


class Fetcher_File_From_WFStorage extends Fetcher
{

	public $wfstorages;  // a Wikifiles_GenericStorage descendant, or array of these


	# ----- Tools ----- #


	protected function read_file_from_wfstorage ( $filename, $storage )
	{
		if ( is_array ( $storage ) )
		{
			foreach ( $storage as $array_element )
			{
				$file = $this->read_file_from_wfstorage ( $filename, $array_element );
				if ( $file !== false )
					return $file;
			}

			return false;
		}
		else
		{
			return $storage->read ( $filename );
		}
	}


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".ReadFileFromWFStorage";
	}


	protected function process_data ( &$signal )
	{
		if ( ! parent::process_data ( $signal ) )
			return false;

		$title = $signal->data_title ( $this->default_data_key );
		if ( is_null ( $title ) )
			return false;

		$filename = $this->core->info->title_filename ( $title );

		$file = $this->read_file_from_wfstorage ( $filename, $this->wfstorages );
		if ( $file === false )
			return false;

		$result = $this->set_fetched_element ( $signal, $file, "object/file" );

		$this->set_jobdata ( $result, array ( 'filename' => $filename ) );

		return $result;
	}


}
