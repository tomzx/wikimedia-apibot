<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Filters: Wikifiles storage: Generic
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) .
	'/../../../../interfaces/line/filters/sieving/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../libs/_generic.php' );



abstract class Filter_WikifilesStorage_Generic extends Filter_Sieving
{


	protected $storage;


	# ----- Constructor ----- #

	function __construct ( $core, $storage )
	{
		parent::__construct ( $core, NULL );
		$this->storage = $storage;
	}


	# ----- Implemented ----- #


	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".Wikifiles_Storage";
	}


	protected function job_params ()
	{
		return array();
	}


	protected function element_id_string ( &$signal )
	{
		return $signal->data_title ( $this->default_data_key );
	}


}
