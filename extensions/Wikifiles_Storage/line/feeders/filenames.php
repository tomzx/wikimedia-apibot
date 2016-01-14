<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Wikifiles storage filenames feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Feeder_WikifilesStorage_Filenames extends Feeder_WikifilesStorage
{


	# ----- Implemented ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Files";
	}


	protected function feed_data_signals ()
	{
		while ( true )
		{
			$filename = $this->wfstorage->list_file();
			if ( $filename === false )
				break;

			$data_signal = $this->data_signal ( $filename, $this->data_type() );

			if ( is_null ( $this->feed_data_signal ( $data_signal ) ) )
				return false;
		}

		return true;
	}


	protected function data_type ()
	{
		return "string/filename";
	}


}
