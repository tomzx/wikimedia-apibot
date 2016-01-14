<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Embeddedin.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Embeddedin extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "embeddedin";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 10900 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "ei",
			'generator' => true,
			'params' => array (
				'namespace' => array (
					'type' => "namespace",
				),
				'continue' => array (
					'type' => "string",
				),
				'limit' => array (
					'type' => "limit",
					'max' => 500,
					'default' => 10,
				),
			),
		);

		if ( $mwverno >= 11100 )
		{
			$paramdesc['params']['title'] = array (
				'type' => "string",
				'required' => true,
			);
			$paramdesc['params']['filterredir'] = array (
				'type' => array (
					"all",
					"redirects",
					"nonredirects",
				),
				'default' => "all",
			);
		}

		return $paramdesc;
	}


}
