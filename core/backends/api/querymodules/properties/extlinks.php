<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Properties: Extlinks.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Property_Extlinks extends API_Params_Property
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "extlinks";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11100 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "el",
			'params' => array (
			),
		);

		if ( $mwverno >= 11300 )
		{
			$paramdesc['params']['limit'] = array (
				'type' => "limit",
				'max' => 500,
				'default' => 10,
			);
			$paramdesc['params']['offset'] = array (  // continue
				'type' => "string",
			);
		}

		if ( $mwverno >= 12300 )  // some might be earlier - didn't checked that properly
		{
			$paramdesc['params']['protocol'] = array (
				'type' => "string",  // actually "uri_protocol", but how to check these in hardwired conditions?
			);
			$paramdesc['params']['query'] = array (
				'type' => "string",
			);
			$paramdesc['params']['expandurl'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		return $paramdesc;
	}


}
