<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backend-independent Query: Pageset: Generic.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );


abstract class Query_Pageset extends Query
{

	# ----- Overriding ----- #


	protected function postprocess_result ( $result )
	{
		if ( $this->settings['return_objects'] )
			$result = new Page ( $this->core, $result );

		return parent::postprocess_result ( $result );
	}


	# ----- Implemented ----- #


	protected function query_family_name ()
	{
		return "pageset";
	}


}
