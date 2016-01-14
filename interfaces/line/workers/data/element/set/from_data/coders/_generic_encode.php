<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Set data: From data: JSON Encode
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



abstract class Worker_DataElement_GenericEncode extends Worker_DataElement_Coder
{


	# ----- Overriding ----- #


	protected function check_and_modify_element ( $element )
	{
		return $this->encode ( parent::check_and_modify_element ( $element ) );
	}


	# ----- Abstract ----- #


	abstract protected function encode ( $element );


}
