<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Allcategories.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Allcategories extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "allcategories";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11200 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "ac",
			'generator' => true,
			'params' => array (
				'from' => array (
					'type' => "string",
				),
				'prefix' => array (
					'type' => "string",
				),
				'dir' => array (
					'type' => array (
						"ascending",
						"descending",
					),
					'default' => "ascending",
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
			$paramdesc['params']['prop'] = array (
				'type' => array (
					"size",
					"hidden",
				),
				'multi' => true,
				'default' => "",
				'limit' => 50,
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
			$paramdesc['params']['min'] = array (
				'type' => "integer",
			);
			$paramdesc['params']['max'] = array (
				'type' => "integer",
			);
		}

		return $paramdesc;
	}


}
