<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic File element diapazone filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_file_stat.php' );
require_once ( dirname ( __FILE__ ) . '/../_checks/diap.php' );


abstract class Filter_DirEntry_File_Diap extends
	Filter_DirEntry_File_WithStat
{

	# ----- Constructor ----- #

	function __construct ( $core, $min, $max )
	{
		parent::__construct ( $core, array ( 'min' => $min, 'max' => $max ) );
	}


	# ----- Instantiating ----- #

	protected function checker ( $checker_params = NULL )
	{
		return new Checker_Diap ( $checker_params );
	}


}
