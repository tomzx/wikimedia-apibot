<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Watchlistraw.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Watchlistraw extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "watchlistraw";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && ( $mwverno < 10900 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "wr",
			'generator' => true,
			'params' => array (
				'continue' => array (
					'type' => "string",
				),
				'namespace' => array (
					'type' => "namespace",
					'multi' => true,
					'limit' => 50,
				),
				'limit' => array (
					'type' => "limit",
					'max' => 50,
					'default' => 10,
				),
				'prop' => array (
					'type' => array (
						"changed",
					),
					'multi' => true,
					'limit' => 50,
				),
				'show' => array (
					'type' => array (
						"changed",
						"!changed",
					),
					'multi' => true,
					'limit' => 50,
				),
			),
		);

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['owner'] = array (
				'type' => "user",
			);
			$paramdesc['params']['token'] = array (
				'type' => "string",
			);
		}

		return $paramdesc;
	}


}
