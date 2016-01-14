<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic File PWUID data filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_file.php' );


abstract class Filter_DirEntry_File_PWUID extends
	Filter_DirEntry_File
{

	# ----- Constructor ----- #

	function __construct ( $core, $checker_params )
	{
		$this->default_data_key = "pwuid";
		parent::__construct ( $core, $checker_params );
	}


	# ----- Overriding ----- #

	protected function element_to_check ( &$signal )
	{
		if ( ! $signal->exists_data_block ( 'stat' ) )
			$signal->set_data_element ( 'stat',
				stat ( $signal->data_element ( '*' ) ) );

		if ( ! $signal->exists_data_block ( $this->default_data_key ) )
		{
			$stat = $signal->data_element ( 'stat' );
			$signal->set_data_element ( $this->default_data_key,
				posix_getpwuid ( $stat['uid'] ) );
		}

		if ( $signal->exists_data_block ( $this->default_data_key ) )
			return parent::element_to_check ( $signal );
		else
			return NULL;
	}


}
