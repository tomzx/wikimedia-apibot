<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Data: Element: Set: From data: Copy.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_CopyData extends Worker_DataElement_FromData
{


	# ----- Implemented ----- #


	protected function check_and_modify_element ( $element )
	{
		return $element;
	}


}
