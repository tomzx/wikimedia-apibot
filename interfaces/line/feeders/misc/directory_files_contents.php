<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Directory (and possibly subdirs) members feeder class
#
#  Don't use this feeder with too large files, if you don't have enough of RAM!
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/directory.php' );



class Feeder_Directory_Files_Contents extends Feeder_Directory
{


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() .
			".Files_Contents";
	}


	# ----- Overriding ----- #

	public function feed_data_signal ( &$signal )
	{
		$filename = $signal->data_element ( "*" );
		if ( is_file ( $filename ) )
			$content = file_get_contents ( $filename );
		else
			return false;

		$signal->set_data_element ( "*", $content );

		return parent::feed_data_signal ( $signal );
	}


	protected function data_type ()
	{
		return "string/*";
	}


}
