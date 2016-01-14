<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Meta: Filerepoinfo.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Meta_Filerepoinfo extends API_Params_Query_Meta
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "filerepoinfo";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 12200 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "fri",
			'params' => array (
				'prop' => array (
					'type' => array (
						"apiurl",
						"name",
						"displayname",
						"rooturl",
						"local",
					),
					'multi' => true,
					'limit' => 50,
				),
			),
		);

		return $paramdesc;
	}


}
