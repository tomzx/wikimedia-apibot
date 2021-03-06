<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - User blockexpiry is in a diapazone filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/diap_times_expiry.php' );


class Filter_User_BlockExpiry_Diap extends Filter_User_BlockExpiry
{

	# ----- Constructor ----- #

	function __construct ( $core, $min, $max )
	{
		parent::__construct ( $core, array ( 'min' => $min, 'max' => $max ) );
	}


	# ----- Instantiating ----- #

	protected function checker ( $checker_params = NULL )
	{
		return new Checker_Diap_Times_Expiry ( $checker_params );
	}


}
