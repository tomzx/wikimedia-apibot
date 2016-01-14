<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Allusers.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Allusers extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "allusers";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 10900 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "au",
			'params' => array (
				'from' => array (
					'type' => "string",
				),
				'prefix' => array (
					'type' => "string",
				),
				'group' => array (
					'type' => array (
						"bot",
						"bureaucrat",
						"sysop",
					),
					'multi' => true,
					'default' => "",
					'limit' => 50,
				),
				'prop' => array (
					'type' => array (
						"editcount",
						"groups",
					),
					'multi' => true,
					'default' => "",
					'limit' => 50,
				),
				'limit' => array (
					'type' => "limit",
					'max' => 500,
					'default' => 10,
				),
			),
		);

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['prop']['type'][] = "blockinfo";
			$paramdesc['params']['prop']['type'][] = "registration";
		}

		if ( $mwverno >= 11500 )
		{
			$paramdesc['params']['witheditsonly'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['to'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['prop']['type'][] = "implicitgroups";
			$paramdesc['params']['prop']['type'][] = "rights";

			$paramdesc['params']['activeusers'] = array (
				'type' => "boolean",
				'default' => false,
			);
			$paramdesc['params']['dir'] = array (
				'type' => array (
					"ascending",
					"descending",
				),
				'default' => "ascending",
			);
			$paramdesc['params']['excludegroup'] = array (
				'type' => array (
					"bot",
					"bureaucrat",
					"sysop",
				),
				'multi' => true,
				'default' => "",
				'limit' => 50,
			);
			$paramdesc['params']['rights'] = array (
				'type' => array (  // not sure if all are present in 1.12
					"apihighlimits",
					"block",
					"bot",
					"edit",
					"markbotedits",
					"minoredit",
					"read",
					"move",
					"noratelimit",
					"patrol",
					"protect",
					"purge",
					"rollback",
					"undelete",
					"upload",
					"userrights",
					"writeapi",
				),
				'multi' => true,
				'default' => "",
				'limit' => 50,
			);
		}

		return $paramdesc;
	}


}
