<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Regexes match General user filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


abstract class Filter_General_User_CheckRegexesExistence extends
	Filter_General_User
{

	# ----- Instantiating ----- #

	protected function slotname_preface ()
	{
		return "User.Regexes";
	}


	protected function items_are_array ( &$items )
	{
		return ( is_array ( $items ) );
	}


	public function item_exists ( $name, $regex )
	{
		return (bool) preg_match ( $regex, $name );
	}


}
