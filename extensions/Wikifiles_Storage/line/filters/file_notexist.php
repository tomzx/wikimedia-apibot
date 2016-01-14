<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Filters: Wikifiles storage: File (and possibly version) does not exist
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) .
	'/../../../../interfaces/line/filters/sieving/_checks/simple_callback.php' );
require_once ( dirname ( __FILE__ ) . '/../../libs/_generic.php' );



class Filter_WikifilesStorage_FileNotExist extends Filter_WikifilesStorage_Generic
{


	# ----- Implemented ----- #


	protected function checker ( $checker_params = NULL )
	{
		return new Checker_WithSimpleCallback ( NULL,
			array ( $this, "file_not_exist" ) );
	}



	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".FileNotExist";
	}


	# ----- New ----- #


	public function file_not_exist ( $file )
	{
		return ! $this->storage->exists ( $file );
	}


}
