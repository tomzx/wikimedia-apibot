<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Action: Block.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/block.php' );


class API_Action_Block extends API_Action
{

	public $anononly;
	public $nocreate;
	public $autoblock;
	public $noemail;

	public $expiry;

	public $reason;


	# ----- Overriding ----- #


	public function nohooks__set_params ( $hook_object, $params )
	{
		if ( ! isset ( $params['gettoken'] ) )
			$this->set_token ( $params, $this->backend->tokens->block_token() );

		return parent::nohooks__set_params ( $hook_object, $params );
	}


	# ----- Implemented ----- #


	protected function module ( $settings )
	{
		return new API_Module_Block ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


}
