<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic signal data element setting from general data class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Worker_DataElement_FromData_WikiValues
	extends Worker_DataElement_FromData
{


	# ----- Constructor ----- #


	function __construct ( $core, $new_element_type )
	{
		$this->new_element_type = $new_element_type;
		parent::__construct ( $core );
	}


	# ----- Implemented ----- #

	// not used by this branch - they override new_element_value() directly
	protected function check_and_modify_element ( $element ) {}

}
