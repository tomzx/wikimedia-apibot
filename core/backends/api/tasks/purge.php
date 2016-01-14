<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Purge Page Cache.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_changing.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/purge.php' );




class API_Task_Purge extends API_Task_Changing
{

	# ----- Implemented ----- #

	protected function api_action ()
	{
		return new API_Action_Purge ( $this->backend );
	}


	protected function action_rights ()
	{
		return array ( "purge" );
	}


	# ----- Entry points ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( empty ( $params['titles'] ) )
		{
			$this->log ( "Cannot purge empty titles", LL_ERROR );
			return false;
		}

		if ( is_array ( $params['titles'] ) )
			$params['titles'] = implode ( '|', $params['titles'] );

		if ( $this->simulate ( 'Would purge the cache(s) of page(s): $titles',
			$params ) )
			return true;

		if ( strpos ( '|', $params['titles'] ) !== false )
			$logbeg = "Pages " . $params['titles'] . " cache";
		else
			$logbeg = "Page [[" . $params['titles'] . "]] cache";
		$actiondesc = "purged";

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


}
