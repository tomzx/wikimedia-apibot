<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Move.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Module_Move extends API_Module
{

	protected $mustbeposted = true;


	# ----- Implemented ----- #

	public function modulename ()
	{
		return "move";
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11200 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "",
			'readrights' => true,
			'writerights' => true,
			'mustbeposted' => true,
			'params' => array (
				'from' => array (
					'type' => "string",
				),
				'to' => array (
					'type' => "string",
					'required' => true,
				),
				'token' => array (
					'type' => "string",
				),
				'reason' => array (
					'type' => "string",
				),
				'movetalk' => array (
					'type' => "boolean",
					'default' => false,
				),
				'noredirect' => array (
					'type' => "boolean",
					'default' => false,
				),
			),
		);

		if ( $mwverno >= 11300 )
		{
			$paramdesc['params']['watch'] = array (
				'type' => "boolean",
				'default' => false,
			);
			$paramdesc['params']['unwatch'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['fromid'] = array (
				'type' => "integer",
			);
		}

		if ( $mwverno >= 11500 )
		{
			$paramdesc['params']['movesubpages'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['ignorewarnings'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['watchlist'] = array (
				'type' => array (
					"watch",
					"unwatch",
					"preferences",
					"nochange",
				),
				'default' => "preferences",
			);

			$paramdesc['params']['watch']['deprecated'] = true;
			$paramdesc['params']['unwatch']['deprecated'] = true;
		}

		return $paramdesc;
	}


	# ----- Overriding ----- #

	public function params ()
	{
		if ( isset ( $this->params['from'] ) && isset ( $this->params['fromid'] ) )
			$this->log ( "Both 'from' and 'fromid' parameters specified - " .
				"the wiki might ignore the 'from'", LL_DEBUG );

		return parent::params();
	}


}
