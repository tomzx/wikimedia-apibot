<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Iwbacklinks.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Iwbacklinks extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "iwbacklinks";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11700 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "iwbl",
			'generator' => true,
			'params' => array (
				'continue' => array (
					'type' => "string",
				),
				'title' => array (
					'type' => "string",
				),
				'prefix' => array (
					'type' => "string",
				),
				'limit' => array (
					'type' => "limit",
					'max' => 500,
					'default' => 10,
				),
				'prop' => array (
					'type' => array (
						"prefix",
						"title",
					),
					'multi' => true,
					'default' => "",
					'limit' => 50,
				),
			),
		);

		return $paramdesc;
	}

}
