<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Parse Page.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_parse.php' );



class API_Task_ParsePage extends API_Task_GenericParse
{

	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['title'] = $this->resolve_page_title ( $params['title'] ) )
			=== NULL )
			return false;

		$logbeg = "Page [[" . $params['title'] . "]]";
		$actiondesc = "parsed" . ( $params['pst'] ? " with PST" : "" ) .
			( empty ( $params['uselang'] )
				? ""
				: " with " . $params['uselang'] . " language"
			);

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


}
