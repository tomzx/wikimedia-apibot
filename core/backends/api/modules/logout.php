<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Logout.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Module_Logout extends API_Module
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "logout";
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 10700 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "",
			'params' => array (
			),
		);

		return $paramdesc;
	}


}
