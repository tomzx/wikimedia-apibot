<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic Delete class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Writer_Wiki_GenericDelete extends Writer_Wiki_Generic
{

	public $reason   = NULL;
	public $watch    = NULL;
	public $oldimage = NULL;


	# ----- Instantiated ----- #

	protected function signal_log_slot_name ()
	{
		return "Delete";
	}


	protected function task_paramnames ()
	{
		return array_merge (
			parent::task_paramnames(),
			array (
				"reason",
				"watch",
				"oldimage",
			)
		);
	}


}
