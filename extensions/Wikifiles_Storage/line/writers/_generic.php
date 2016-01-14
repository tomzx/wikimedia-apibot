<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Wikifiles storage generic writer.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) .
	'/../../../../interfaces/line/writers/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../libs/_generic.php' );


abstract class Writer_WikifilesStorage_Generic extends Writer
{

	protected $storage;


	# ----- Constructor ----- #


	function __construct ( $core, $storage )
	{
		parent::__construct ( $core );
		$this->storage = $storage;
	}


	# ----- Instantiated ----- #


	protected function signal_log_slot_name ()
	{
		return "Wikifiles_Storage";
	}


	# ----- Overriding ----- #


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$file = $signal->data_element ( $this->default_data_key );
		if ( is_null ( $file ) )
			return false;

		$result = $this->storage_action ( $file );

		$this->set_jobdata ( $result );

		return $result;
	}


	# ----- Abstract ----- #


	abstract protected function storage_action ( $file );


}
