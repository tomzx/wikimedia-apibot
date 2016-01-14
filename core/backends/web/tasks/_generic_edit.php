<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Task: Generic Page Edit (submit).
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_changing.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/edit.php' );



abstract class Web_Task_GenericEdit extends Web_Task_Changing
{

	# ----- Implemented ----- #

	protected function action ()
	{
		return new Web_Action_Edit ( $this->backend );
	}


}
