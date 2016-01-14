<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Help.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Module_Help extends API_Module
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "help";
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		$paramdesc = array (
			'prefix' => "",
			'params' => array (
			),
		);

		return $paramdesc;
	}


}
