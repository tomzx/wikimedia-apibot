<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backends: API: Backend.
#
#  Container for the base bot backend-specific objects.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic/backend.php' );
require_once ( dirname ( __FILE__ ) . '/common/exchanger.php' );
require_once ( dirname ( __FILE__ ) . '/common/identity.php' );
require_once ( dirname ( __FILE__ ) . '/common/info.php' );
require_once ( dirname ( __FILE__ ) . '/common/tokens.php' );


class Backend_API extends Backend
{


	public $tokens;


	# ----- Overriding ----- #


	protected function create_backend_objects ()
	{
		parent::create_backend_objects();

		$this->tokens = $this->new_tokens ( $this->exchanger, $this->info,
			$this->hooks, $this->settings );
	}


	# ----- Implemented ----- #


	public function backend_name ()
	{
		return "API";
	}


	public function backend_software ()
	{
		return "MediaWiki";
	}


	public function nohooks__new_exchanger ( $hook_object,
		$url, $settings, $defaults, $log, $browser, $hooks )
	{
		return new Exchanger_API ( $url, $settings, $defaults, $log, $browser,
			$hooks );
	}


	public function nohooks__new_identity ( $hook_object,
		$exchanger, $infostore, $hooks, $settings, $backend_name )
	{
		return new Identity_API ( $exchanger, $infostore, $hooks, $settings,
			$backend_name );
	}


	public function nohooks__new_info ( $hook_object,
		$exchanger, $infostore, $identity, $hooks, $settings )
	{
		return new Info_API ( $exchanger, $infostore, $identity, $hooks, $settings );
	}


	public function nohooks__new_tokens ( $hook_object,
		$exchanger, $info, $hooks, $settings )
	{
		return new Tokens_API ( $exchanger, $info, $hooks, $settings );
	}


}
