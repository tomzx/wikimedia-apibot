<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Set param: From param class
#  (Propagates also the Set param class for children that don't need a From)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Worker_SetParam_Checksum extends Worker_SetParam_FromParam
{

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
