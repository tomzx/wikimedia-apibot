<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Page Undo (last revisions).
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_edit.php' );
require_once ( dirname ( __FILE__ ) . '/fetch_title.php' );



class API_Task_Undo extends API_Task_GenericEdit
{

	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( isset ( $params['page'] ) )
		{
			$page = $params['page'];
			unset ( $params['page'] );

			if ( is_object ( $page ) && ( $page instanceof Page ) )
				$page = $page->data();
		}
		else
		{
			$page = array();
		}

		if ( empty ( $params['undo'] ) && isset ( $page['revid'] ) )
			$params['undo'] = $this->resolve_element ( $page, 'revid' );
		if ( empty ( $params['undoafter'] ) && isset ( $page['to_revid'] ) )
			$params['undoafter'] = $this->resolve_element ( $page, 'to_revid' );

		$params['title'] = $this->resolve_element ( $params['title'], 'title' );
		if ( empty ( $params['title'] ) )
		{
			$this->log ( "Cannot undo page without a title", LL_ERROR );
			return false;
		}

		if ( is_null ( $params['undo'] ) )
		{
			$this->log ( "Fetching [[" . $params['title'] . "]] last revid to undo...",
				LL_DEBUG );
			$fetch = new API_Task_FetchTitle ( $this->backend );
			$properties = array (
				'revisions' => array (
					'prop' => "ids",
					'limit' => 1,
				),
			);
			$page = $fetch->fetch ( $params['title'], $properties, NULL, NULL, false );
			$params['undo'] = $this->resolve_element ( $page, 'revid' );
			unset ( $page );
			unset ( $fetch );
		}

		if ( $this->simulation ( 'Would undo page [[$title]], revid $undo' .
			( is_null ( $params['undoafter'] )
				? ""
				: " to revid " . $params['undoafter']
			),
			$params ) )
			return true;

		$setnames = array (
			array ( 'minor' => array ( 'true' => "minor", 'false' => "notminor" ) ),
		);

		$logbeg = "Page [[" . $params['title'] . "]]";
		$actiondesc = "undone revid " . $params['undo'];
		if ( ! is_null ( $params['undoafter'] ) )
			$actiondesc .= " to revid " . $params['undoafter'];

		return $this->act_and_log ( $logbeg, $actiondesc, $params, $setnames );
	}


}
