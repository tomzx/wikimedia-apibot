<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic autofetcher class
#
#  ATTENTION: The autofetchers are NOT line objects, and cannot be strung
#  into a line! They are just pieces that can be used by the line objects.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


abstract class Autofetcher
{

	protected $core;
	protected $owner_name;
	protected $check_callback;

	protected $fetcher;


	# ----- Constructor ----- #

	function __construct ( $core, $owner_name, $check_callback )
	{
		$this->core = $core;
		$this->owner_name = $owner_name;
		$this->check_callback = $check_callback;
	}


	# ----- Entry point ----- #

	public function check_and_fetch ( &$signal )
	{
		if ( call_user_func ( $this->check_callback, $signal ) )
		{
			if ( ! isset ( $this->fetcher ) )
				$this->fetcher = $this->new_fetcher();

			return $this->fetcher->process ( $signal );
		}

		return true;
	}


	# ----- Abstract ----- #

	abstract protected function new_fetcher ();


}
