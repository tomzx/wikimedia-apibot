<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Param: Set: String generic class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Worker_SetParam_String extends Worker_SetParam_FromParam
{


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".String";
	}


	# ----- Implemented ----- #


	protected function check_and_modify_paramvalue ( $from_value )
	{
		if ( ! is_string ( $from_value ) )
		{
			$this->log ( 'The paramvalue is not string - cannot proceed!', LL_ERROR );
			return NULL;
		}

		return $this->modify_paramvalue ( $from_value );
	}


	# ----- Abstract ----- #


	abstract protected function modify_paramvalue ( $from_value );


}
