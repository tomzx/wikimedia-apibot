<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Block user Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Writer_Wiki_Block extends Writer_Wiki_Generic
{

	public $reason = NULL;

	public $expiry = NULL;

	public $anononly  = NULL;
	public $nocreate  = NULL;
	public $autoblock = NULL;
	public $noemail   = NULL;


	# ----- Instantiated ----- #

	protected function signal_log_slot_name ()
	{
		return "Block";
	}


	protected function task_paramnames ()
	{
		return array_merge (
			parent::task_paramnames(),
			array (
				"reason",
				"expiry",
				"anononly",
				"nocreate",
				"autoblock",
				"noemail",
			)
		);
	}


}
