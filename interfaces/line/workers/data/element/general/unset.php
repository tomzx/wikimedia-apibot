<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Data element: Unset a value.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../../../../../libs/misc/subelements.php' );



class Worker_DataElement_Unset extends Worker_DataElement
{


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Unset";
	}


	# ----- Implemented ----- #


	protected function new_element ( &$signal )
	{
		$element = $signal->data_element ( $this->default_data_key );
		return unset_subelement ( $element, $this->sublevels );
	}


}
