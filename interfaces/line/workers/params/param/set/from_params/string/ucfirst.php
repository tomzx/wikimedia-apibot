<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Param: Set: Strings: Uppercase first letter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_SetParam_String_UCFirst extends Worker_SetParam_String
{


	public $mb = true;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".UCFirst";
	}


	protected function modify_paramvalue ( $from_value )
	{
		if ( $this->mb )
			return mb_ucfirst ( $from_value );
		else
			return ucfirst ( $from_value );
	}


}
