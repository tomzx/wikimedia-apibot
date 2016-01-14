<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic Move page Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


abstract class Writer_Wiki_GenericMove extends Writer_Wiki_Generic
{

	public $reason       = NULL;

	public $to_title     = NULL;

	public $noredirect   = NULL;
	public $movetalk     = NULL;
	public $movesubpages = NULL;
	public $watch        = NULL;


	# ----- Instantiated ----- #

	protected function signal_log_slot_name ()
	{
		return "Move";
	}


	protected function task_paramnames ()
	{
		return array_merge (
			parent::task_paramnames(),
			array (
				"reason",
				"to_title",
				"noredirect",
				"movetalk",
				"movesubpages",
				"watch",
			)
		);
	}


}
