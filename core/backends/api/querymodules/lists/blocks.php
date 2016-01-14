<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Blocks.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Blocks extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "blocks";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11200 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "bk",
			'params' => array (
				'start' => array (
					'type' => "timestamp",
				),
				'end' => array (
					'type' => "timestamp",
				),
				'dir' => array (
					'type' => array (
						"newer",
						"older",
					),
					'default' => "older",
				),
				'ids' => array (
					'type' => "integer",
					'multi' => true,
					'limit' => 50,
				),
				'users' => array (
					'type' => "string",  // MW API says type is string, not user
					'multi' => true,
					'limit' => 50,
				),
				'prop' => array (
					'type' => array (
						"id",
						"user",
						"by",
						"timestamp",
						"expiry",
						"reason",
						"range",
						"flags",
					),
					'multi' => true,
					'default' => "id|user|by|timestamp|expiry|reason|flags",
					'limit' => 50,
				),
				'limit' => array (
					'type' => "limit",
					'max' => 500,
					'default' => 10,
				),
			),
		);

		if ( $mwverno >= 11300 )
		{
			$paramdesc['params']['ip'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['prop']['type'][] = "userid";
			$paramdesc['params']['prop']['type'][] = "byid";
		}

		if ( $mwverno >= 11900 )
		{
			$paramdesc['params']['show'] = array (
				'type' => array (
					"account",
					"!account",
					"temp",
					"!temp",
					"ip",
					"!ip",
					"range",
					"!range",
				),
				'multi' => true,
				'default' => "",
				'limit' => 50,
			);
		}

		return $paramdesc;
	}


	# ----- Overriding ----- #

	public function set_param ( $name, $value )
	{  // 'users' and 'ip' should not be used together
		if ( ( $name == "ip" ) && isset ( $this->params['users'] ) )
			return false;
		if ( ( $name == "users" ) && isset ( $this->params['ip'] ) )
			return false;
		return parent::set_param ( $name, $value );
	}


}
