<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Properties: Links.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Property_Links extends API_Params_Property
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "links";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11100 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "pl",
			'generator' => true,
			'params' => array (
				'namespace' => array (
					'type' => "namespace",
					'multi' => true,
					'limit' => 50,
				),
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

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['titles'] = array (
				'type' => "string",
				'multi' => true,
				'limit' => 50,
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

		return $paramdesc;
	}


}
