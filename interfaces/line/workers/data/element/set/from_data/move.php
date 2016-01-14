<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Data: Element: Set: From data: Move.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_MoveData extends Worker_DataElement_Set
{


	# ----- Implemented ----- #


	protected function new_element_value ( &$signal )
	{
		$element = $signal->data_element ( $this->from_data_key ( $signal ) );
		$sub = get_subelement ( $element, $this->from_sublevels );

		unset_subelement ( $element, $this->from_sublevels );
		$signal->set_data_element ( $element, $data_key );

		return $sub;
	}


}
