<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic File Group name filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_file.php' );
require_once ( dirname ( __FILE__ ) . '/../_checks/match_items_any.php' );
require_once ( dirname ( __FILE__ ) . '/../_checks/_standard_check_callbacks.php' );


abstract class Filter_DirEntry_File_Namepart extends
	Filter_DirEntry_File
{

	# ----- Constructor ----- #

	function __construct ( $core, $regexes )
	{
		$this->default_data_key = "pathinfo";

		if ( ! is_array ( $regexes ) )
			$regexes = array ( $regexes );

		parent::__construct ( $core, $regexes );
	}


	# ----- Overriding ----- #

	protected function element_to_check ( &$signal )
	{
		if ( ! $signal->exists_data_block ( $this->default_data_key ) )
			$signal->set_data_element ( $this->default_data_key,
				pathinfo ( $signal->data_element ( '*' ) ) );

		if ( $signal->exists_data_block ( $this->default_data_key ) )
			return parent::element_to_check ( $signal );
		else
			return NULL;
	}


	# ----- Implemented ----- #

	protected function checker ( $checker_params = NULL )
	{
		return new Checker_MatchItems_Any ( $checker_params,
			"check_callback__match_regex_withneg" );
	}


}
