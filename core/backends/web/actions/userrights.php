<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Action: Userrights.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/userrights.php' );


class Web_Action_Userrights extends Web_Action
{

	public $add;     // an array or bar-separated list of groups
	public $remove;  // an array or bar-separated list of groups

	public $reason;


	# ----- Overriding ----- #


	public function nohooks__set_params ( $hook_object, $params )
	{
		if ( ! isset ( $params['user'] ) )
		{
			$this->log (
				"User rights change requested without specifying the user - cannot go!",
				LL_ERROR );
			return false;
		}

		$this->set_token ( $params, $this->backend->tokens->userrights_token(),
			$params['user'] );

		return parent::nohooks__set_params ( $hook_object, $params );
	}


	# ----- Implemented ----- #


	protected function module ( $settings )
	{
		return new Web_Module_Userrights ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


}
