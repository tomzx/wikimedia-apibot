<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Expand Page Templates.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_changing.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/expandtemplates.php' );




class API_Task_Expandtemplates extends API_Task_Changing
{

	# ----- Implemented ----- #

	protected function api_action ()
	{
		return new API_Action_Expandtemplates ( $this->backend );
	}


	protected function action_rights ()
	{
		return array ( "expandtemplates" );
	}


	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['title'] = $this->resolve_string_param ( $params['title'], 'title' ) )
			=== NULL )
			return false;

		$logbeg = "The text";
		$actiondesc = "template-expanded";

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


}
