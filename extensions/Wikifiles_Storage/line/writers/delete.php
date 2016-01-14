<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Wikifiles storage Delete writer.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../libs/archive.php' );



class Writer_WikifilesStorage_Delete extends Writer_WikifilesStorage_Generic
{


	public $archive_storage;  // a storage where files will be copied before deletion


	# ----- Tools ----- #


	protected function archive_file ( $file, $storage )
	{
		$file = $this->storage->read ( $file );
		$archive_storage->append ( $file );
	}


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Delete";
	}


	# ----- Instantiated ----- #


	protected function storage_action ( $file )
	{
		if ( isset ( $this->archive_storage ) )
			$this->archive_file ( $file, $this->archive_storage );

		return $this->storage->delete ( $file );
	}


}
