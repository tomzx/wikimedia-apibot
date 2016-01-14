<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Wikifiles storage Adopt writer.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../libs/archive.php' );



class Writer_WikifilesStorage_Adopt extends Writer_WikifilesStorage_Generic
{


	public $extra_storage;  // wikifiles storage object, or array of such ones

	public $timestamp;  // timestamp to adopt if _Archive ("*" - all)

	public $move = false;  // if true, delete the file from the extra storage


	# ----- Tools ----- #


	protected function adopt_file ( $file, $extra_storage, $move = false )
	{
		if ( is_array ( $extra_storage ) )
		{
			foreach ( $extra_storage as $array_element )
				if ( $this->adopt_file ( $file, $array_element, $move ) )
					return true;

			return false;
		}
		else
		{
			return $this->storage->adopt ( $file, $extra_storage, $move );
		}
	}


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Adopt";
	}


	# ----- Instantiated ----- #


	protected function storage_action ( $file )
	{
		if ( isset ( $this->extra_storage ) )
			return $this->adopt_file ( $file, $this->extra_storage, $this->move );

		return false;
	}


}
