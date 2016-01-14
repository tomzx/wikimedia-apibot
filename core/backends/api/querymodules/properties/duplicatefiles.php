<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Properties: Duplicatefiles.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Property_Duplicatefiles extends API_Params_Property
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "duplicatefiles";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11400 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "df",
			'generator' => true,
			'params' => array (
				'limit' => array (
					'type' => "limit",
					'max' => 500,
					'default' => 10,
				),
				'continue' => array (
					'type' => "string",
				),
			),
		);

		if ( $mwverno >= 12300 )  // some might be earlier - didn't checked that properly
		{
			$paramdesc['params']['dir'] = array (
				'type' => array (
					"ascending",
					"descending",
				),
				'default' => "ascending",
			);
			$paramdesc['params']['localonly'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		return $paramdesc;
	}


}
