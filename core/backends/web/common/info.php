<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Info: Web backend
#
#  Info fetching.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../../_generic/info.php' );


class Info_Web extends Info_Generic
{


	# ----- Implemented ----- #


	public function nohooks__load_info ( $hook_object, $type )
	{
		$this->log ( "Currently the Web backend cannot obtain MediaWiki info!",
			LL_PANIC );
		die();
	}


	protected function load_all_info ()
	{
	}


	protected function backend_name ()
	{
		return "Web";
	}


	protected function default_info_settings ()
	{
		return array();
	}


}
