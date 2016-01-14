<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: Generator: Templates.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_with_pageset.php' );


class API_Query_Generator_Templates extends API_Query_Generator_WithPageset
{

	# ----- Implemented ----- #

	public function queryname ()
	{
		return "templates";
	}


}
