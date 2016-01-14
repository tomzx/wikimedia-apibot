<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Generic Page Import.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_changing.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/import.php' );



abstract class API_Task_GenericImport extends API_Task_Changing
{

	# ----- Implemented ----- #

	protected function api_action ()
	{
		return new API_Action_Import ( $this->backend );
	}


	protected function action_rights ()
	{
		return array ( "import" );
	}


}
