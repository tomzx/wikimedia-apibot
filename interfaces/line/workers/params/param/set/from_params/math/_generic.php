<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic signal params modifier class - Math
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Worker_SetParam_Math extends Worker_SetParam_FromParam
{


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Math";
	}


	# ----- Implemented ----- #


	protected function check_and_modify_paramvalue ( $from_value )
	{
		if ( ! is_numeric ( $from_value ) )
		{
			$this->log ( 'The paramvalue is not numeric - cannot proceed!', LL_ERROR );
			return NULL;
		}

		return $this->modify_paramvalue ( $from_value );
	}


	# ----- Abstract ----- #


	abstract protected function modify_paramvalue ( $from_value );


}
