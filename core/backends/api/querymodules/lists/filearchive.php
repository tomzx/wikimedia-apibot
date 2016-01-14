<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Allpages.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Filearchive extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "filearchive";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11700 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "ai",
			'params' => array (
				'prop' => array (
					'type' => array (
						"timestamp",
						"user",
						"size",
						"dimensions",
						"mime",
						"sha1",
						"metadata",
						"description",
					),
					'default' => "timestamp|url",
					'multi' => true,
					'limit' => 50,
				),
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
				'minsize' => array (
					'type' => "integer",
				),
				'maxsize' => array (
					'type' => "integer",
				),
				'limit' => array (
					'type' => "limit",
					'max' => 500,
					'default' => 10,
				),
				'sha1' => array (
					'type' => "string",
				),
				'sha1base36' => array (
					'type' => "string",
				),
			),
		);

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['prop']['type'][] = "parseddescription";
			$paramdesc['params']['prop']['type'][] = "bitdepth";

			$paramdesc['params']['to'] = array (
				'type' => "string",
			);
		}

		return $paramdesc;
	}


}
