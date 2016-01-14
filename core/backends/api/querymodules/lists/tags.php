<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Tags.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Tags extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "tags";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11600 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "tg",
			'params' => array (
				'continue' => array (
					'type' => "string",
				),
				'limit' => array (
					'type' => "limit",
					'max' => 50,
					'default' => 10,
				),
				'prop' => array (
					'type' => array (
						"name",
						"displayname",
						"description",
						"hitcount",
					),
					'multi' => true,
					'default' => "name",
				),
			),
		);

		return $paramdesc;
	}


}
