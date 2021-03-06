<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generals with Bot property generic filter class
#
#  Does not use the checker object extension!
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );


abstract class Filter_General_Bot extends Filter_WikiObject
{

	# ----- Constructor ----- #

	function __construct ( $core, $checker_params = NULL )
	{
		$this->data_property = "bot";
		parent::__construct ( $core, $checker_params );
	}

	# ----- Overriding ----- #

	protected function element_to_check ( &$signal )
	{
		$is_bot = parent::element_to_check ( $signal );
		return $is_bot;
	}


	# ----- Instantiating ----- #

	protected function job_params ()
	{
		return array();
	}

	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".Bot";
	}


}
