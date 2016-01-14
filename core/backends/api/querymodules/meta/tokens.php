<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Meta: Tokens.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Meta_Tokens extends API_Params_Query_Meta
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "tokens";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 12400 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "",
			'params' => array (
				'prop' => array (
					'type' => array (
						"csrf",
						"watch",
						"patrol",
						"rollback",
						"userrights",
					),
				),
			),
		);

		return $paramdesc;
	}


}
