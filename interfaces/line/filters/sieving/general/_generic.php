<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic General Filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Filter_WikiObject extends Filter_Sieving
{


	# ----- Instantiating ----- #


	protected function element_id_string ( &$signal )
	{
		$id = $signal->data_unique_id ( $this->default_data_key );
		if ( is_null ( $id ) )
			$id = "---unknown wiki object id (something must be wrong!)---";

		return "General id " . $id;
	}


	protected function slotname_preface ()
	{
		return "General";
	}


}
