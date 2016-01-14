<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Action: Delete.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/delete.php' );


class Web_Action_Delete extends Web_Action
{

	public $watch;

	public $reason;


	# ----- Overriding ----- #


	public function nohooks__set_params ( $hook_object, $params )
	{
		$this->set_token ( $params, $this->backend->tokens->delete_token() );

		return parent::nohooks__set_params ( $hook_object, $params );
	}


	# ----- Overriding ----- #

	protected function module ( $settings )
	{
		return new Web_Module_Delete ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


}
