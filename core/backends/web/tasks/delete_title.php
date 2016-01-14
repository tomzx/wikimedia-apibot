<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Task:  Delete Page by Title.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_delete.php' );



class Web_Task_DeleteTitle extends Web_Task_GenericDelete
{


	# ----- Tools ----- #

	protected function check_result ( $data, $logbeg, $actdesc )
	{
		if ( preg_match ( '/content *= *"noindex,nofollow"/Uus', $data ) )
		{
			if ( strpos ( $data, 'pt-login' ) !== false )
			{
				$this->log ( "Error: We are not logged in", LL_ERROR );
			}
			elseif ( strpos ( $data, '<div class="permissions-errors">' ) !== false )
			{
				$this->log ( "Error: No permission to delete pages", LL_ERROR );
			}
			elseif ( strpos ( $data, '<div class="error mw-error-cannotdelete">' ) !== false )
			{
				$this->log ( "Error: Page was (probably) already deleted", LL_ERROR );
			}
			else
			{
				$this->log ( "Error: Unknown error", LL_ERROR );
			}
			return false;
		}
		else
		{
			return true;
		}
	}


	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['title'] = $this->resolve_page_title ( $params['title'] ) )
			=== NULL )
			return false;

		if ( $this->simulation ( "Would delete page [[$title]]", $params ) )
			return true;

		$logbeg = "Page [[" . $params['title'] . "]]";

		return $this->delete_title_or_pageid ( $logbeg, $params );
	}


}
