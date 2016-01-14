<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic Upload Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Writer_Wiki_GenericUpload extends Writer_Wiki_Generic
{

	public $text           = NULL;
	public $watch          = NULL;
	public $ignorewarnings = NULL;


	# ----- Instantiated ----- #

	protected function signal_log_slot_name ()
	{
		return "Upload";
	}


	protected function task_paramnames ()
	{
		return array (
			"text",
			"watch",
			"ignorewarnings",
		);
	}


}
