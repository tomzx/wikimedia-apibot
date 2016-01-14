<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Users.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Users extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "users";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11200 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "us",
			'params' => array (
				'users' => array (
					'type' => "string",
					'multi' => true,
					'limit' => 50,
				),
				'prop' => array (
					'type' => array (
						"blockinfo",
						"groups",
						"editcount",
					),
					'multi' => true,
					'default' => "",
					'limit' => 50,
				),
			),
		);

		if ( $mwverno >= 11300 )
		{
			$paramdesc['params']['prop']['type'][] = "registration";
		}

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['prop']['type'][] = "emailable";
		}

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['prop']['type'][] = "gender";

			$paramdesc['params']['token'] = array (
				'type' => array (
					"userrights",
				),
				'multi' => true,
				'limit' => 50,
			);
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['prop']['type'][] = "rights";
		}

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['prop']['type'][] = "implicitgroups";
		}

		return $paramdesc;
	}


}
