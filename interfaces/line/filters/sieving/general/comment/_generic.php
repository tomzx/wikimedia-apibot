<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - General by comments generic filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );


abstract class Filter_General_Comment extends Filter_WikiObject
{

	# ----- Constructor ----- #

	function __construct ( $core, $checker_params )
	{
		$this->data_property = "comment";
		parent::__construct ( $core, $checker_params );
	}


	# ----- Instantiating ----- #

	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".Comment";
	}


}
