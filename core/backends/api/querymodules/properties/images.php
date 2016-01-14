<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Properties: Images.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Property_Images extends API_Params_Property
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "images";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11100 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "im",
			'generator' => true,
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
			$paramdesc['params']['continue'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11900 )
		{
			$paramdesc['params']['dir'] = array (
				'type' => array (
					"ascending",
					"descending",
				),
				'default' => "ascending",
			);
		}

		if ( $mwverno >= 12300 )  // might be earlier - didn't checked that properly
		{
			$paramdesc['params']['images'] = array (
				'type' => "string",
				'multi' => true,
				'limit' => 50,
			);
		}

		return $paramdesc;
	}


}
