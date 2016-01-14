<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Action: Rollback.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/rollback.php' );


class API_Action_Rollback extends API_Action
{

	public $title;
	public $user;
	public $summary;
	public $markbot;


	# ----- Overriding ----- #


	public function nohooks__set_params ( $hook_object, $params )
	{
		if ( ! isset ( $params['user'] ) )
		{
			$this->log (
				"Rollback requested without specifying the user - cannot go!",
				LL_ERROR );
			return false;
		}

		$this->set_token ( $params,
			$this->backend->tokens->rollback_token ( $params['title'],
				( isset ( $params['user'] ) ? $params['user'] : NULL ) ),
			$params['user'] );

		return parent::nohooks__set_params ( $hook_object, $params );
	}


	# ----- Implemented ----- #


	protected function module ( $settings )
	{
		return new API_Module_Rollback ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


}
