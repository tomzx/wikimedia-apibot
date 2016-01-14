<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Edit.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Module_Edit extends API_Module
{

	protected $mustbeposted = true;


	# ----- Implemented ----- #

	public function modulename ()
	{
		return "edit";
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11300 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "",
			'readrights' => true,
			'writerights' => true,
			'mustbeposted' => true,
			'params' => array (
				'title' => array (
					'type' => "string",
					'required' => true,
				),
				'token' => array (
					'type' => "string",
				),
				'section' => array (
					'type' => "string",
				),
				'text' => array (
					'type' => "string",
				),
				'summary' => array (
					'type' => "string",
				),
				'minor' => array (
					'type' => "boolean",
					'default' => false,
				),
				'notminor' => array (
					'type' => "boolean",
					'default' => false,
				),
				'bot' => array (
					'type' => "boolean",
					'default' => false,
				),
				'basetimestamp' => array (
					'type' => "timestamp",
				),
				'recreate' => array (
					'type' => "boolean",
					'default' => false,
				),
				'createonly' => array (
					'type' => "boolean",
					'default' => false,
				),
				'nocreate' => array (
					'type' => "boolean",
					'default' => false,
				),
				'captchaword' => array (
					'type' => "string",
				),
				'captchaid' => array (
					'type' => "string",
				),
				'watch' => array (
					'type' => "boolean",
					'default' => false,
				),
				'unwatch' => array (
					'type' => "boolean",
					'default' => false,
				),
				'md5' => array (
					'type' => "string",
				),
				'prependtext' => array (
					'type' => "string",
				),
				'appendtext' => array (
					'type' => "string",
				),
			),
		);

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['starttimestamp'] = array (
				'type' => "timestamp",
			);
		}

		if ( $mwverno >= 11500 )
		{
			$paramdesc['params']['undo'] = array (
				'type' => "integer",
			);
			$paramdesc['params']['undoafter'] = array (
				'type' => "integer",
			);
		}

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['watch']['deprecated'] = true;
			$paramdesc['params']['unwatch']['deprecated'] = true;

			$paramdesc['params']['watchlist'] = array (
				'type' => array (
					"watch",
					"unwatch",
					"preferences",
					"nochange",
				),
				'default' => "preferences",
			);
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['redirect'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		if ( $mwverno >= 11900 )
		{
			$paramdesc['params']['sectiontitle'] = array (
				'type' => "string",
			);
		}

		return $paramdesc;
	}


	# ----- Overriding ----- #


	protected function split_paramvalue_on_pipes ( $name )
	{
		if ( ( $name == 'text' ) ||
			( $name == 'prependtext' ) ||
			( $name == 'appendtext' )
		)
			return false;
		else
			return parent::split_paramvalue_on_pipes ( $name );
	}


}
