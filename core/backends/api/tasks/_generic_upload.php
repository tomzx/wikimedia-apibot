<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Generic Upload File.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_changing.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/upload.php' );



abstract class API_Task_GenericUpload extends API_Task_Changing
{

	# ----- Implemented ----- #

	protected function api_action ()
	{
		return new API_Action_Upload ( $this->backend );
	}


}
