<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: backend: Identity.
#
#  Provides login, logout and other identity management.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../../_generic/identity.php' );


class Identity_Web extends Identity
{


	# ----- Implemented ----- #


	protected function set_cookies_names ( $cookieprefix )
	{
		$this->log ( "The Web backend, as of now, is unable to provide identity management!",
			LL_PANIC );
			die();
	}


	protected function full_login ( $account, $wiki )
	{
		$this->log ( "The Web backend, as of now, is unable to provide identity management!",
			LL_PANIC );
			die();
	}


	protected function full_logout ()
	{
		$this->log ( "The Web backend, as of now, is unable to provide identity management!",
			LL_PANIC );
			die();
	}


}
