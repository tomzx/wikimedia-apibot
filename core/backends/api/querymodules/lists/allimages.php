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


class API_Params_List_Allimages extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "allimages";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11200 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "ai",
			'generator' => true,
			'params' => array (
				'prop' => array (
					'type' => array (
						"timestamp",
						"user",
						"comment",
						"url",
						"size",
						"dimensions",
						"mime",
						"sha1",
						"metadata",
					),
					'multi' => true,
					'default' => "timestamp|url",
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

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['to'] = array (
				'type' => "string",
			);
			$paramdesc['params']['prop']['type'][] = "userid";
			$paramdesc['params']['prop']['type'][] = "thumbmime";
			$paramdesc['params']['prop']['type'][] = "archivename";
			$paramdesc['params']['prop']['type'][] = "bitdepth";
		}

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['mime'] = array (  // disabled in Misermode.
				'type' => "string",
			);
		}

		return $paramdesc;
	}


}
