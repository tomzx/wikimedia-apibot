<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Module: Fetch.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Web_Module_Fetch extends Web_Module
{

	# ----- Tools ----- #

	private function antibot_template_protected_text ( $text, $username )
	{
		return (bool) preg_match (
			'/\{\{(' .
				'nobots|' .
				'bots\|allow=none|' .
				'bots\|deny=all|' .
				'bots\|optout=all|' .
				'bots\|deny[^\|\}]*[\=\,]\s*' .
					preg_quote ( $username, '/' ) . '\s*[\,\|\}]' .
			')\}\}/iS',
		$text );
	}


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


	# ----- Implemented ----- #

	protected function hardcoded_paramdesc ( $mw_version_number )
	{
		return array
		(
			'params' => array
			(
				'action' => array
				(
					'type' => "string",
					'required' => true,
					'default' => "edit",
				),
				'title' => array
				(
					'type' => "string",
					'varname' => "title",
					'required' => true,
				),
				'section' => array
				(
					'type' => "string",
					'varname' => "section",
				),
				'oldid' => array
				(
					'type' => "integer",
					'varname' => "oldid",
				),
			),
		);
	}


	protected function data ()
	{
		$html = parent::data();
		$data = array();

		$cs_params = $this->config_set ( $html );
		$data['title'  ] = trim ( $cs_params['wgTitle'], '"' );
		$data['pageid' ] = trim ( $cs_params['wgArticleId'], '"' );

		$data['protection'] = array();
		if ( isset ( $cs_params['wgRestrictionEdit'] ) &&
			( $cs_params['wgRestrictionEdit'] != "[]" ) )
			$data['protection'][] = array ( 'type' => "edit",
				'level' => trim ( $cs_params['wgRestrictionEdit'], "[]" ) );
		if ( isset ( $cs_params['wgRestrictionMove'] ) &&
			( $cs_params['wgRestrictionMove'] != "[]" )  )
			$data['protection'][] = array ( 'type' => "move",
				'level' => trim ( $cs_params['wgRestrictionMove'], "[]" ) );

		$data['section'       ] = $this->input_value ( $html, "wpSection" );
		$data['timestamp'     ] = $this->input_value ( $html, "wpEdittime" );
		$data['fetchtimestamp'] = $this->input_value ( $html, "wpStarttime" );
		$data['comment'       ] = $this->input_value ( $html, "wpSummary" );
		$data['autosummary'   ] = $this->input_value ( $html, "wpAutoSummary" );
		$data['oldid'         ] = $this->input_value ( $html, "oldid" );

		$data['revid'] = $this->input_value ( $html, "oldid" );
		if ( empty ( $data['revid'] ) )
			$data['revid'] = $cs_params['wgCurRevisionId'];

		$data['text'] = $this->textarea_value ( $html, "wpTextbox1" );
		$data['size'] = strlen ( $data['text'] );

		$data['nobottemplate'] =
			$this->antibot_template_protected_text ( $data['text'],
				$this->backend->info->user_name() );
		$data['md5'] = md5 ( $data['text'] );

		$data['token'] = $this->input_value_noquotes ( $html, "wpEditToken" );

		$this->backend->tokens->set_edit_token ( $data['token'] );

		return $data;
	}


}
