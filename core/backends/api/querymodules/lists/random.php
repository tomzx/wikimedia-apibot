<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Random.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Random extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "random";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11200 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "rn",
			'generator' => true,
			'params' => array (
				'namespace' => array (
					'type' => "namespace",
					'multi' => true,
					'limit' => 50,
				),
				'redirect' => array (
					'type' => "boolean",
					'default' => false,
				),
				'limit' => array (
					'type' => "limit",
					'max' => 10,
					'default' => 1,
				),
			),
		);

		return $paramdesc;
	}


}
