<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Page specified property is in a diapazone filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../_checks/diap.php' );


class Filter_Page_SpecifiedProperty_Diap extends
	Filter_Page_SpecifiedProperty
{

	# ----- Constructor ----- #

	function __construct ( $property, $core, $min, $max )
	{
		parent::__construct ( $property, $core, array ( 'min' => $min, 'max' => $max ) );
	}


	# ----- Instantiating ----- #

	protected function checker ( $checker_params = NULL )
	{
		return new Checker_Diap ( $checker_params );
	}


}
