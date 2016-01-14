<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Page On-need autofetcher auxilliary class
#
#  ATTENTION: The autofetchers are NOT line objects, and cannot be strung
#  into a line! They are just pieces that can be used by the line objects.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../wiki/page.php' );


class Autofetcher_Page extends Autofetcher
{

	public $create_missing_pages;

	protected $page_properties;


	# ----- Constructor ----- #

	function __construct ( $core, $owner_name, $check_callback,
		$page_properties = NULL )
	{
		$this->page_properties = $page_properties;
		parent::__construct ( $core, $owner_name, $check_callback );
	}


	# ----- Implemented ----- #

	protected function new_fetcher ()
	{
		$this->core->log->log ( $this->owner_name .
			": Must fetch pages - spawning an internal Fetch.Page object...",
			LL_WARNING );

		$fetcher = new Fetcher_Wiki_Page ( $this->core );

		if ( ! is_null ( $this->page_properties ) )
			$fetcher->properties = $this->page_properties;
		if ( isset ( $this->create_missing_pages ) )
			$fetcher->create_missing_pages = $this->create_missing_pages;

		return $fetcher;
	}


}
