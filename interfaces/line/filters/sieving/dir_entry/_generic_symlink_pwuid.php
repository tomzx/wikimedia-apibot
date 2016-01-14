<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic Symlink PWUID data filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_symlink.php' );


abstract class Filter_DirEntry_Symlink_PWUID extends
	Filter_DirEntry_Symlink
{

	# ----- Constructor ----- #

	function __construct ( $core, $checker_params )
	{
		$this->default_data_key = "lpwuid";
		parent::__construct ( $core, $checker_params );
	}


	# ----- Overriding ----- #

	protected function element_to_check ( &$signal )
	{
		if ( ! $signal->exists_data_block ( 'lstat' ) )
			$signal->set_data_element ( 'lstat',
				stat ( $signal->data_element ( '*' ) ) );

		if ( ! $signal->exists_data_block ( $this->default_data_key ) )
		{
			$stat = $signal->data_element ( 'lstat' );
			$signal->set_data_element ( $this->default_data_key,
				posix_getpwuid ( $stat['uid'] ) );
		}

		if ( $signal->exists_data_block ( $this->default_data_key ) )
			return parent::element_to_check ( $signal );
		else
			return NULL;
	}


}
