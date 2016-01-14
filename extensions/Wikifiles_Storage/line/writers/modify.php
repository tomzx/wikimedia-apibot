<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Wikifiles storage Modify writer.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../libs/archive.php' );



class Writer_WikifilesStorage_Modify extends Writer_WikifilesStorage_Generic
{

	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Modify";
	}


	# ----- Instantiated ----- #


	protected function storage_action ( $file )
	{
		return $this->storage->Modify ( $file );
	}


}
