<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic "symlink with statdata" filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_symlink.php' );


abstract class Filter_DirEntry_Symlink_WithStat extends
	Filter_DirEntry_Symlink
{

	# ----- Constructor ----- #

	function __construct ( $core, $checker_params )
	{
		$this->default_data_key = "lstat";
		parent::__construct ( $core, $checker_params );
	}


	# ----- Overriding ----- #

	protected function element_to_check ( &$signal )
	{
		if ( ! $signal->exists_data_block ( $this->default_data_key ) )
			$signal->set_data_element ( $this->default_data_key,
				stat ( $signal->data_element ( '*' ) ) );

		if ( $signal->exists_data_block ( $this->default_data_key ) )
			return parent::element_to_check ( $signal );
		else
			return NULL;
	}


}
