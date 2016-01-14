<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Page Import XML.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_import.php' );



class API_Task_ImportXML extends API_Task_GenericImport
{

	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( $this->simulation ( "Would import page from a XML file" ) )
			return true;

		$logbeg = "Page (from XML file)";
		$actiondesc = "imported";

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


}
