<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Task: generic Page Fetch.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_fetch.php' );



abstract class Web_Task_FetchPage extends Web_Task_Fetching
{

	# ----- Implemented ----- #

	protected function action ()
	{
		return new Web_Action_Query ( $this->backend );
	}


	# ----- Fetching support ----- #

	# --- Extract config set --- #

	protected function config_set ( $html )
	{
		$regex = '/mw.config.set\(\{(.*)\}\)/Uus';
		if ( ! preg_match ( $regex, $html, $matches ) )
			return NULL;

		$rp_name = '\"([^\"]+)\"';
		$rp_value = '(null' .
			'|true' .
			'|false' .
			'|\d+' .
			'|(?U)\{(' .
				'[^\{\}]*\{(' .
					'[^\{\}]*\{(' .
						'[^\{\}]*\{(' .
							'[^\{\}]*\{(' .
								'[^\{\}]*\{(' .
									'[^\{\}]*' .
								')\}' .
								'|[^\{\}]*' .
							')\}' .
							'|[^\{\}]*' .
						')\}' .
						'|[^\{\}]*' .
					')\}' .
					'|[^\{\}]*' .
				')\}' .
				'|[^\{\}]*' .
			')\}(?-U)' .
			'|(?U)\[(' .
				'[^\[\]]*\[(' .
					'[^\[\]]*\[(' .
						'[^\[\]]*\[(' .
							'[^\[\]]*\[(' .
								'[^\[\]]*\[(' .
									'[^\[\]]*' .
								')\]' .
								'|[^\[\]]*' .
							')\]' .
							'|[^\[\]]*' .
						')\]' .
						'|[^\[\]]*' .
					')\]' .
					'|[^\[\]]*' .
				')\]' .
				'|[^\[\]]*' .
			')\](?-U)' .
			'|(?U)\"([^\"]*(\\\"[^\"]*)*)\")(?-U)';
		$regex = '/' . $rp_name . '\:\s*' . $rp_value . '/us';
		if ( ! preg_match_all ( $regex, $matches[1], $matches, PREG_SET_ORDER ) )
			return NULL;

		$params = array();
		foreach ( $matches as $match )
			$params[$match[1]] = $match[2];

		return $params;
	}


	# --- Extract inputs --- #

	protected function textarea_value ( $html, $name )
	{
		$regex = '/\<textarea [^\>]*name=\"' . $name . '\"[^\>]*\>' .
			'(.*)\<\/textarea/Uus';
		if ( preg_match ( $regex, $html, $matches ) )
			return trim ( $matches[1] );
		else
			return NULL;
	}

	protected function input_value ( $html, $name )
	{
		$regex = '/\<input [^\>]*name=\"' . $name . '\"[^\>]*\/\>/Uus';
		if ( preg_match ( $regex, $html, $matches ) )
		{
			$regex = '/value=\"(([^\"]*(\\\"[^\"]*)*))\"/Uus';
			if ( preg_match ( $regex, $matches[0], $matches ) )
				return trim ( $matches[1] );
		}
		return NULL;
	}

	protected function input_value_noquotes ( $html, $name )
	{
		$regex = '/\<input [^\>]*name=\"' . $name . '\"[^\>]*\/\>/Uus';
		if ( preg_match ( $regex, $html, $matches ) )
		{
			$regex = '/value=\"([^\"]*)\"/Uus';
			if ( preg_match ( $regex, $matches[0], $matches ) )
				return trim ( $matches[1] );
		}
		return NULL;
	}


	# --- Page data --- #

	protected function page_data ()
	{
		$html = parent::data();
		$page = array();

		$cs_params = $this->config_set ( $html );
		$page['title'  ] = trim ( $cs_params['wgTitle'], '"' );
		$page['pageid' ] = trim ( $cs_params['wgArticleId'], '"' );

		$page['protection'] = array();
		if ( isset ( $cs_params['wgRestrictionEdit'] ) &&
			( $cs_params['wgRestrictionEdit'] != "[]" ) )
			$page['protection'][] = array ( 'type' => "edit",
				'level' => trim ( $cs_params['wgRestrictionEdit'], "[]" ) );
		if ( isset ( $cs_params['wgRestrictionMove'] ) &&
			( $cs_params['wgRestrictionMove'] != "[]" )  )
			$page['protection'][] = array ( 'type' => "move",
				'level' => trim ( $cs_params['wgRestrictionMove'], "[]" ) );

		$page['section'       ] = $this->input_value ( $html, "wpSection" );
		$page['timestamp'     ] = $this->input_value ( $html, "wpEdittime" );
		$page['fetchtimestamp'] = $this->input_value ( $html, "wpStarttime" );
		$page['comment'       ] = $this->input_value ( $html, "wpSummary" );
		$page['autosummary'   ] = $this->input_value ( $html, "wpAutoSummary" );
		$page['oldid'         ] = $this->input_value ( $html, "oldid" );

		$page['revid'] = $this->input_value ( $html, "oldid" );
		if ( empty ( $page['revid'] ) )
			$page['revid'] = $cs_params['wgCurRevisionId'];

		$page['text'] = $this->textarea_value ( $html, "wpTextbox1" );
		$page['size'] = strlen ( $page['text'] );

		$page['token'] = $this->input_value_noquotes ( $html, "wpEditToken" );

		$this->backend->tokens->set_edit_token ( $page['token'] );  // convenient

		if ( isset ( $page['text'] ) )
			$page['md5'] = md5 ( $page['text'] );

		return $page;
	}


	# --- Check the xfer result --- #

	protected function check_result ( $data, $logbeg, $actdesc )
	{
		$text = $this->textarea_value ( $data, "wpTextbox1" );
		return ( ! is_null ( $text ) );
	}


	# ----- Entry point ----- #

	protected function fetch_editable_page ( $logbeg, $params )
	{
		if ( $this->act_and_log ( $logbeg, "fetched", $params ) )
			return $this->page_data();
		else
			return false;
	}


}
