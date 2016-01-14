<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Param: Set: Strings: Uppercase every word class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_SetParam_String_UCWords extends Worker_SetParam_String
{


	public $mb = true;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".UCWords";
	}


	protected function modify_paramvalue ( $from_value )
	{
		if ( $this->mb )
			return mb_ucwords ( $from_value );
		else
			return ucwords ( $from_value );
	}


}
