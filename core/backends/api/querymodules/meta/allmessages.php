<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Meta: Allmessages.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Meta_Allmessages extends API_Params_Query_Meta
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "allmessages";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11200 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "am",
			'params' => array (
				'messages' => array (
					'type' => "string",
					'default' => "*",
					'multi' => true,
					'limit' => 50,
				),
				'filter' => array (
					'type' => "string",
				),
				'lang' => array (
					'type' => "string",
				),
			),
		);

		if ( $mwverno >= 11500 )
		{
			$paramdesc['params']['from'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['prop'] = array (
				'type' => array (
					"default",
				),
				'multi' => true,
				'limit' => 50,
			);
			$paramdesc['params']['enableparser'] = array (
				'type' => "boolean",
				'default' => false,
			);
			$paramdesc['params']['args'] = array (
				'type' => "string",
				'multi' => true,
				'allowsduplicates' => true,
				'limit' => 50,
			);
			$paramdesc['params']['title'] = array (
				'type' => "string",
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
			$paramdesc['params']['customized'] = array (
				'type' => array (
					"all",
					"modified",
					"unmodified",
				),
				'default' => "all",
			);
		}

		if ( $mwverno >= 11900 )
		{
			$paramdesc['params']['includelocal'] = array (
				'type' => "boolean",
				'default' => false,
			);
			$paramdesc['params']['nocontent'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		return $paramdesc;
	}


}
