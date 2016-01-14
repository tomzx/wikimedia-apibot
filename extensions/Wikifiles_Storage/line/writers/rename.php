<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Wikifiles storage Rename writer.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../libs/archive.php' );



class Writer_WikifilesStorage_Rename extends Writer_WikifilesStorage_Generic
{

	public $new_name;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Rename";
	}


	# ----- Instantiated ----- #


	protected function storage_action ( $file )
	{
		if ( empty ( $this->new_name ) )
			return false;
		else
			return $this->storage->rename ( $file, $this->new_name );
	}


}
