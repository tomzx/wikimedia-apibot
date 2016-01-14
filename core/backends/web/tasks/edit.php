<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Task: Page Edit (submit).
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_edit.php' );



class Web_Task_Edit extends Web_Task_GenericEdit
{


	# ----- Constructor ----- #

	function __construct ( $backend, $params = array(), $logpreface = "" )
	{
		parent::__construct ( $backend, $params, $logpreface );

		if ( ! isset ( $this->settings['honor_nobottemplate'] ) )
			$this->settings['honor_nobottemplate'] = true;
	}


	# ----- Tools ----- #

	private function is_page_with_lastrev ( $page )
	{
		return ( isset ( $page['lastrevid'] ) && isset ( $page['revid'] ) &&
			( $page['lastrevid'] == $page['revid'] ) );
	}


	protected function check_result ( $data, $logbeg, $actdesc )
	{
		if ( preg_match ( '/content *= *"noindex,nofollow"/Uus', $data ) )
		{
			if ( preg_match ( '/\<div class\=[\"\']previewnote[\"\']\>/u', $data ) )
			{
				$this->log ( "Error: Bad or missing edit token", LL_ERROR );
				return false;
			}
			return true;
		}
		else
		{
			if ( strpos ( $data, 'pt-login' ) !== false )
			{
				$this->log ( "Error: We are not logged in", LL_ERROR );
			}
			elseif ( preg_match ( '/\<textarea [^\>]*id=[\'\"]wpTextbox2[\'\"]/Uus',
				$data, $matches ) )
			{
				$this->log ( "Error: Edit conflict", LL_ERROR );
			}
			elseif ( preg_match ( '/\<input [^\>]*id=[\'\"]wpRecreate[\'\"]/Uus',
				$data, $matches ) )
			{
				$this->log ( "Error: Page was deleted meanwhile", LL_ERROR );
			}
			else
			{
				return true;
			}
			return false;
		}
	}


	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( isset ( $params['page'] ) )
		{
			$page = $params['page'];
			unset ( $params['page'] );
		}
		else
		{
			$this->log ( "No page given to edit!", LL_ERROR );
			return false;
		}


		if ( is_object ( $page ) && ( $page instanceof Page ) )
			$page = $page->data();

		if ( empty ( $page['title'] ) )
		{
			$this->log ( "Cannot edit page without a title", LL_ERROR );
			return false;
		}
		$params['title'] = $page['title'];

		$params['text'] = $page['text'];

		if ( $this->simulation ( "Would edit page [[$title]]:\n" . $params['text'],
			$params ) )
			return true;

		$logbeg = "Page [[" . $params['title'] . "]]";
		if ( isset ( $page['nobottemplate'] ) && $page['nobottemplate'] &&
			$this->settings['honor_nobottemplate'] )
		{
			$this->log ( $logbeg . " denies write with a {{Bots}} template",
				LL_ERROR );
			return false;
		}

		$params['md5'] = md5 ( $params['text'] );
		if ( $page['md5'] == $params['md5'] )
		{
			$this->log ( "Page [[" . $params['title'] .
				"]] was not changed - will not submit it", LL_WARNING );
			return true;
		}

		if ( isset ( $page['section'] ) )
			$params['section'] = $page['section'];

		if ( isset ( $page['timestamp'] ) && $this->is_page_with_lastrev ( $page ) )
			$params['basetimestamp'] = $page['timestamp'];
		if ( isset ( $page['fetchtimestamp'] ) )
			$params['starttimestamp'] = $page['fetchtimestamp'];

		if ( isset ( $page['autosummary'] ) )
			$params['autosummary'] = $page['autosummary'];
		if ( isset ( $page['oldid'] ) )
			$params['oldid'] = $page['oldid'];

		$setnames = array (
			'minor' => array ( 'true' => "minor", 'false' => "notminor" ),
		);

		return $this->act_and_log ( $logbeg, "submitted", $params, $setnames );
	}


}
