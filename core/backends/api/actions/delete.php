<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Action: Delete.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/delete.php' );


class API_Action_Delete extends API_Action
{

	public $watch;

	public $reason;


	# ----- Constructor ----- #


	function __construct ( $backend, $settings = array(), $logpreface = "" )
	{
		$this->setnames = array (
			'watch'   => array ( 'true' => "watch", 'false' => "unwatch" ),
			'unwatch' => array ( 'true' => "unwatch", 'false' => "watch" ),
		);
		parent::__construct ( $backend, $settings, $logpreface );
	}


	# ----- Overriding ----- #


	public function nohooks__set_params ( $hook_object, $params )
	{
		$this->set_token ( $params, $this->backend->tokens->delete_token() );

		return parent::nohooks__set_params ( $hook_object, $params );
	}


	# ----- Implemented ----- #


	protected function module ( $settings )
	{
		return new API_Module_Delete ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


}
