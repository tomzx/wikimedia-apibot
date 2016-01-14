<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Set data: From data: String CRC32
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Worker_DataElement_Checksum extends Worker_DataElement_FromData
{


	# ----- Overriding ----- #


	protected function check_and_modify_element ( $element )
	{
		if ( ! is_string ( $element ) )
		{
			$this->log ( "The element to checksum is not a string - cannot proceed!",
				LL_ERROR );
			return NULL;
		}

		return $this->checksum ( $element );
	}


	# ----- Abstract ----- #


	abstract protected function checksum ( $element );


}
