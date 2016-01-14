<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - User On-need autofetcher auxilliary class
#
#  ATTENTION: The autofetchers are NOT line objects, and cannot be strung
#  into a line! They are just pieces that can be used by the line objects.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../wiki/user.php' );


class Autofetcher_User extends Autofetcher
{

	protected $user_properties;


	# ----- Constructor ----- #

	function __construct ( $core, $owner_name, $check_callback,
		$user_properties = NULL )
	{
		$this->user_properties = $user_properties;
		parent::__construct ( $core, $owner_name, $check_callback );
	}


	# ----- Implemented ----- #

	protected function new_fetcher ()
	{
		$this->core->log ( $this->owner_name .
			": Must fetch users - spawning an internal Fetch.User object...",
			LL_WARNING );

		$fetcher = new Fetcher_Wiki_User ( $this->core );
		if ( isset ( $this->user_properties ) )
			$fetcher->properties = $this->user_properties;

		return $fetcher;
	}


}
