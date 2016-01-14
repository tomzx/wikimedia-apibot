<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Page Edit (submit).
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_edit.php' );



class API_Task_Edit extends API_Task_GenericEdit
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

		if ( isset ( $page['prependtext'] ) )
			$text = $page['prependtext'];
		elseif ( isset ( $page['appendtext'] ) )
			$text = $page['appendtext'];
		elseif ( isset ( $page['text'] ) )
			$text = $page['text'];
		else
		{
			$this->log ( "Empty pages are not OK - refusing to write [[" .
				$params['title'] . "]]", LL_ERROR );
			return false;
		}

		if ( ! is_string ( $text ) )
		{
			$this->log ( "Page [[" . $page['title'] .
				"]] new text is not a string! Something is wrong!", LL_PANIC );
			die();
		}

		if ( $this->simulation ( "Would edit page [[" . $page['title'] .
			"]]:\n$text", $params ) )
			return true;

		$logbeg = "Page [[" . $params['title'] . "]]";
		if ( isset ( $page['nobottemplate'] ) &&
			$page['nobottemplate'] &&
			$this->settings['honor_nobottemplate'] )
		{
			$this->log ( $logbeg . " denies write with a {{Bots}} template",
				LL_ERROR );
			return false;
		}

		$params['md5'] = md5 ( $text );
		if ( ! empty ( $page['md5'] ) && ( $page['md5'] == $params['md5'] ) )
		{
			$this->log ( "Page [[" . $page['title'] .
				"]] was not changed - will not submit it", LL_WARNING );
			return true;
		}

		if ( ! empty ( $page['prependtext'] ) )
			$params['prependtext'] = $text;
		elseif ( ! empty ( $page['appendtext'] ) )
			$params['appendtext'] = $text;
		else
			$params['text'] = $text;

		if ( isset ( $page['section'] ) )
			$params['section'] = $page['section'];
		if ( isset ( $page['sectiontitle'] ) )
			$params['sectiontitle'] = $page['sectiontitle'];

		if ( isset ( $page['timestamp'] ) && $this->is_page_with_lastrev ( $page ) )
			$params['basetimestamp'] = $page['timestamp'];
		if ( isset ( $page['fetchtimestamp'] ) )
			$params['starttimestamp'] = $page['fetchtimestamp'];

		$setnames = array (
			'minor' => array ( 'true' => "minor", 'false' => "notminor" ),
		);

		return $this->act_and_log ( $logbeg, "submitted", $params, $setnames );
	}


}
