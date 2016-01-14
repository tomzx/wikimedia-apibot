<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Task: Generic fetch file.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #

require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../data/file.php' );



abstract class Task_Fetch_File extends Task_Fetch
{

	# ----- Overriding ----- #

	protected function postprocess_result ( $file )
	{
		if ( ( $file === false ) || ( $file === NULL ) )
			return $file;

		$file = parent::postprocess_result ( $file );

		if ( $this->settings['fetch_objects'] )
			return new File ( $this->core, $file );
		else
			return $file;
	}


}
