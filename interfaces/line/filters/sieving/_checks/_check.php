<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic filter checker class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


abstract class Checker
{


	protected $params;


	# ----- Constructor ----- #


	function __construct ( $params )
	{
		$this->params = $params;
	}


	# ----- Externally accessible ----- #


	public function params ()
	{
		return $this->params;
	}


	# ----- Abstract ----- #


	abstract public function check ( $signal );

	abstract public function job_name ();


}
