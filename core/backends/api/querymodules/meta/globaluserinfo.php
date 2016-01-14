<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Meta: Globaluserinfo.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Meta_Globaluserinfo extends API_Params_Query_Meta
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "globaluserinfo";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11500 ) )  // just guess it appeared then
			return NULL;

		$paramdesc = array (
			'prefix' => "gui",
			'params' => array (
				'user' => array (
					'type' => "string",
				),
				'prop' => array (
					'type' => array (
						"groups",
						"rights",
						"merged",
						"unattached",
					),
					'multi' => true,
					'limit' => 50,
				),
			),
		);

		return $paramdesc;
	}


}
