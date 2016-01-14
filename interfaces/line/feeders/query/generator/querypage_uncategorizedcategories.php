<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Uncategorizedcategories Querypage Generator feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_querypage.php' );



class Feeder_Query_Generator_Querypage_Uncategorizedcategories extends
	Feeder_Query_Generator_QuerypageGeneric
{

	protected function query ( &$core )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../../../../core/queries/generator/querypage_uncategorizedcategories.php' );
		return new API_Query_Generator_Querypage_Uncategorizedcategories ( $core );
	}


}
