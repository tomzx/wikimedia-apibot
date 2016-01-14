<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Set data: From data: JSON Decode
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



abstract class Worker_DataElement_GenericDecode extends Worker_DataElement_Coder
{


	public $restored_element_type;


	# ----- Overriding ----- #


	protected function new_element_type ( &$signal )
	{
		$this->die_on_nonstring_property ( 'restored_element_type' );

		return $this->restored_element_type;
	}


	protected function check_and_modify_element ( $element )
	{
		if ( ! is_string ( $element ) )
		{
			$this->log ( 'The data to decode is not string - cannot work!',
				LL_PANIC );
			die();
		}

		return $this->decode ( $element );
	}


	# ----- Abstract ----- #


	abstract protected function decode ( $element );


}
