<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: Generator: Querypage: BrokenRedirects.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/querypage.php' );


class API_Query_Generator_Querypage_BrokenRedirects extends
	API_Query_Generator_Querypage
{

	# ----- Constructor ----- #

	function __construct ( $backend, $settings = array(), $defaults = array() )
	{
		parent::__construct ( "BrokenRedirects", $backend, $settings, $defaults );
	}


}
