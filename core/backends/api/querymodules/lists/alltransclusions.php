<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Alltransclusions.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Alltransclusions extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "alltransclusions";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 12100 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "at",
			'generator' => true,
			'params' => array (
				'from' => array (
					'type' => "string",
				),
				'to' => array (
					'type' => "string",
				),
				'prefix' => array (
					'type' => "string",
				),
				'unique' => array (
					'type' => "boolean",
					'default' => false,
				),
				'namespace' => array (
					'type' => "namespace",
				),
				'prop' => array (
					'type' => array (
						"ids",
						"title",
					),
					'multi' => true,
					'default' => "title",
					'limit' => 50,
				),
				'continue' => array (
					'type' => "string",
				),
				'limit' => array (
					'type' => "limit",
					'max' => 500,
					'default' => 10,
				),
				'dir' => array (
					'type' => array (
						"ascending",
						"descending",
					),
					'default' => "ascending",
				),
			),
		);

		return $paramdesc;
	}


}
