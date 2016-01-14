<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Module: Edit.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Web_Module_Edit extends Web_Module
{

	# ----- Constructor ----- #

	function __construct ( $backend, $params = array(), $logpreface = "" )
	{
		parent::__construct ( $backend, $params, $logpreface );

		if ( ! isset ( $this->params['honor_nobottemplate'] ) )
			$this->params['honor_nobottemplate'] = true;
	}


	# ----- Implemented ----- #

	protected function hardcoded_paramdesc ( $mw_version_number )
	{
		return array
		(
			'mustbeposted' => true,
			'params' => array
			(
				'action' => array
				(
					'type' => "string",
					'required' => true,
					'default' => "submit",
				),
				'title' => array
				(
					'type' => "string",
					'required' => true,
				),
				'token' => array
				(
					'type' => "string",
					'varname' => "wpEditToken",
					'required' => true,
				),
				'section' => array
				(
					'type' => "string",
					'varname' => "wpSection",
				),
				'text' => array
				(
					'type' => "string",
					'varname' => "wpTextbox1",
					'required' => true,
				),
				'summary' => array
				(
					'type' => "string",
					'varname' => "wpSummary",
				),
				'minor' => array
				(
					'type' => "boolean",
					'varname' => "wpMinoredit",
					'default' => false,
				),
				'notminor' => array
				(
					'type' => "boolean",
					'varname' => "",
					'default' => false,
				),
				'bot' => array
				(
					'type' => "boolean",
					'varname' => "",
					'default' => false,
				),
				'fetchtimestamp' => array
				(
					'type' => "timestamp",
					'varname' => "wpStarttime",
				),
				'basetimestamp' => array
				(
					'type' => "timestamp",
					'varname' => "wpEdittime",
				),
				'recreate' => array
				(
					'type' => "boolean",
					'varname' => "wpRecreate",
					'default' => false,
				),
				'createonly' => array
				(
					'type' => "boolean",
					'varname' => "",
					'default' => false,
				),
				'nocreate' => array
				(
					'type' => "boolean",
					'varname' => "",
					'default' => false,
				),
				'captchaword' => array
				(
					'type' => "string",
					'varname' => "",  // todo! find the real varname used!
				),
				'captchaid' => array
				(
					'type' => "string",
					'varname' => "",  // todo! find the real varname used!
				),
				'watch' => array
				(
					'type' => "boolean",
					'varname' => "wpWatchthis",
					'default' => false,
				),
				'unwatch' => array
				(
					'type' => "boolean",
					'varname' => "",
					'default' => false,
				),
				'md5' => array
				(
					'type' => "string",
					'varname' => "",
				),
				'prependtext' => array
				(
					'type' => "string",
					'varname' => "",
				),
				'appendtext' => array
				(
					'type' => "string",
					'varname' => "",
				),
				'_ignoreblanksummary' => array
				(
					'type' => "integer",
					'varname' => "wpIgnoreBlankSummary",
					'default' => 1,
				),
				'autosummary' => array
				(
					'type' => "string",
					'varname' => "wpAutoSummary",
				),
				'oldid' => array
				(
					'type' => "integer",
					'varname' => "oldid",
				),
			),
		);
	}


}
