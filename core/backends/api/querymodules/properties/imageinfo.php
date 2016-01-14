<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Properties: Imageinfo.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Property_Imageinfo extends API_Params_Property
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "imageinfo";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11100 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "ii",
			'params' => array (
				'prop' => array (
					'type' => array (
						"timestamp",
						"user",
						"comment",
						"url",
						"size",
						"sha1",
					),
					'multi' => true,
					'default' => "timestamp|user",
					'limit' => 50,
				),
			),
		);

		if ( $mwverno == 11200 )
		{
			$paramdesc['params']['prop']['type'][] = "metadata";

			$paramdesc['params']['limit'] = array (
				'type' => "limit",
				'max' => 500,
				'default' => 1,
			);
			$paramdesc['params']['start'] = array (
				'type' => "timestamp",
			);
			$paramdesc['params']['end'] = array (
				'type' => "timestamp",
			);
			$paramdesc['params']['urlwidth'] = array (
				'type' => "integer",
				'default' => -1,
			);
			$paramdesc['params']['urlheight'] = array (
				'type' => "integer",
				'default' => -1,
			);
		}

		if ( $mwverno == 11100 )
		{
			$paramdesc['params']['history'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		if ( $mwverno >= 11300 )
		{
			$paramdesc['params']['prop']['type'][] = "archivename";
		}

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['prop']['type'][] = "bitdepth";
		}

		if ( $mwverno >= 11500 )
		{
			$paramdesc['params']['continue'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['prop']['type'][] = "dimensions";
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['prop']['type'][] = "userid";
			$paramdesc['params']['prop']['type'][] = "parsedcomment";
			$paramdesc['params']['prop']['type'][] = "thumbmime";
		}

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['metadataversion'] = array (
				'type' => "string",
				'default' => 1,
			);
			$paramdesc['params']['urlparam'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 12300 )  // some might be earlier - didn't checked that properly
		{
			$paramdesc['params']['extmetadatalanguage'] = array (
				'type' => "string",  // actually "interwiki", but how to check these in hardwired conditions?
			);
			$paramdesc['params']['extmetadatamultilang'] = array (
				'type' => "boolean",
				'default' => false,
			);
			$paramdesc['params']['extmetadatafilter'] = array (
				'type' => "string",
				'multi' => true,
			);
		}

		return $paramdesc;
	}


}
