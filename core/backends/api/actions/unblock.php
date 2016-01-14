<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Action: Unblock.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/unblock.php' );


class API_Action_Unblock extends API_Action
{

	public $reason;


	# ----- Overriding ----- #


	public function nohooks__set_params ( $hook_object, $params )
	{
		if ( ! isset ( $params['gettoken' ) )
			$this->set_token ( $params, $this->backend->tokens->unblock_token() );

		return parent::nohooks__set_params ( $hook_object, $params );
	}


	# ----- Implemented ----- #


	protected function module ( $settings )
	{
		return new API_Module_Unblock ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


}
