<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Protect Page.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_changing.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/protect.php' );




class API_Task_Protect extends API_Task_Changing
{

	# ----- Implemented ----- #

	protected function api_action ()
	{
		return new API_Action_Protect ( $this->backend );
	}


	protected function action_rights ()
	{
		return array ( "protect" );
	}


	# ----- Entry points ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['title'] = $this->resolve_page_title ( $params['title'] ) )
			=== NULL )
			return false;

		if ( ! isset ( $params['cascade'] ) )
			$params['cascade'] = false;

		$protections_strings = array();
		foreach ( $params['protections'] as $name => $value )
			$params_strings[] = $name . "=" . $value;
		$protstring = implode ( ",", $protections_strings );

		if ( $this->simulation ( 'Would change page [[$title]] protections to ($protstring)',
			array_merge ( $params, array ( 'protstring' => $protstring ) ) ) )
			return true;

		$logbeg = "Page [[" . $params['title'] . "]] protections";
		$actiondesc = "changed";

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


	# ----- Extra functions ----- #

	public function protect ( $params )
	{
		$params['protections'] = array (
			'edit'     => "sysop",
			'move'     => "sysop",
			'rollback' => "sysop",
			'delete'   => "sysop",
			'restore'  => "sysop",
		);

		return $this->go ( $params );
	}

	public function unprotect ( $params )
	{
		$params['protections'] = array (
			'edit'     => "*",
			'move'     => "autoconfirmed",
			'rollback' => "autoconfirmed",
			'delete'   => "sysop",
			'restore'  => "sysop",
		);

		return $this->go ( $params );
	}

	public function semiprotect ( $params )
	{
		$params['protections'] = array (
			'edit'     => "autoconfirmed",
			'move'     => "autoconfirmed",
			'rollback' => "autoconfirmed",
			'delete'   => "sysop",
			'restore'  => "sysop",
		);

		return $this->go ( $params );
	}


}
