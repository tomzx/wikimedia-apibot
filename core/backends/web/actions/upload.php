<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Action: Upload.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/upload.php' );


class Web_Action_Upload extends Web_Action
{

	public $comment;

	public $text;

	public $watch;
	public $ignorewarnings;


	# ----- Overriding ----- #


	public function nohooks__set_params ( $hook_object, $params )
	{
		$this->set_token ( $params, $this->backend->tokens->upload_token() );

		return parent::nohooks__set_params ( $hook_object, $params );
	}


	# ----- Implemented ----- #


	protected function module ( $settings )
	{
		return new Web_Module_Upload ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


}
