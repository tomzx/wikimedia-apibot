<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Page Import Interwiki.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_import.php' );



class API_Task_ImportInterwiki extends API_Task_GenericImport
{

	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['title'] = $this->resolve_page_title ( $params['title'] ) )
			=== NULL )
			return false;

		if ( empty ( $params['iwcode'] ) )
		{
			$this->log ( "Cannot import page from empty interwiki", LL_ERROR );
			return false;
		}

		if ( $this->simulation ( 'Would import page [[$title]] from interwiki $iwcode' .
			( $params['fullhistory'] ? " with full history" : "" ) .
			( empty ( $params['namespace'] ) ? "" : " into namespace " . $params['namespace'] ) .
			( $params['templates'] ? " with included templates" : "" ),
			$params ) )
			return true;

		$logbeg = "Page [[" . $params['title'] . "]]";
		$actiondesc = "imported";

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


}
