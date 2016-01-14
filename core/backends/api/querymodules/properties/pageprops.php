<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Properties: Pageprops.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Property_Pageprops extends API_Params_Property
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "pageprops";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11800 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "pp",
			'params' => array (
				'continue' => array (
					'type' => "string",
				),
				'prop' => array (
					'type' => "string",
					'multi' => true,
				),
			),
		);

		return $paramdesc;
	}


}
