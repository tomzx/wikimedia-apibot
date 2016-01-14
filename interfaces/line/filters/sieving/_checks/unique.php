<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Filters: Checkers: Check if data is unique (by id).
#
#  Attention: Might need a LOT of memory for the unique ids list!
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_check.php' );



class Checker_Unique extends Checker
{


	protected $passed_ids = array();


	# ----- Implemented ----- #


	public function check ( $id )
	{
		if ( in_array ( $id, $this->passed_ids ) )
			return false;

		$this->passed_ids[] = $id;
		return true;
	}


	public function job_name ()
	{
		return "Unique";
	}


	# ----- New ----- #


	public function reset ()
	{
		$this->passed_ids = array();
	}


}
