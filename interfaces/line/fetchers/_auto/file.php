<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - File On-need autofetcher auxilliary class
#
#  ATTENTION: The autofetchers are NOT line objects, and cannot be strung
#  into a line! They are just pieces that can be used by the line objects.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../wiki/filename.php' );


class Autofetcher_File extends Autofetcher
{

	protected $imageinfo_properties;


	# ----- Constructor ----- #

	function __construct ( $core, $owner_name, $check_callback,
		$imageinfo_properties = NULL )
	{
		$this->imageinfo_properties = $imageinfo_properties;
		parent::__construct ( $core, $owner_name, $check_callback );
	}


	# ----- Implemented ----- #

	protected function new_fetcher ()
	{
		$this->core->log->log ( $this->owner_name .
			": Must fetch pages - spawning an internal Fetch.Filename object...",
			LL_WARNING );

		$fetcher = new Fetcher_Wiki_Filename ( $this->core );

		if ( isset ( $this->imageinfo_properties ) &&
			is_array ( $this->imageinfo_properties )
		)
			foreach ( $this->imageinfo_properties as $name => $value )
				$fetcher->$name = $value;

		return $fetcher;
	}


}
