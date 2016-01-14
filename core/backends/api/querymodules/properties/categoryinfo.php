<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Properties: Categoryinfo.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Property_Categoryinfo extends API_Params_Property
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "categoryinfo";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11300 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "ci",
			'params' => array (
			),
		);

		if ( $mwverno >= 11500 )
		{
			$paramdesc['params']['continue'] = array (
				'type' => "string",
			);
		}

		return $paramdesc;
	}


}
