<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Action: Edit.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/edit.php' );


class API_Action_Edit extends API_Action
{

	public $text;
	public $prependtext;
	public $appendtext;

	public $section;
	public $sectiontitle;

	public $undo;
	public $undoafter;

	public $minor;
	public $bot;

	public $recreate;
	public $createonly;
	public $nocreate;

	public $redirect;

	public $watchlist;

	public $summary;


	# ----- Constructor ----- #


	function __construct ( $backend, $settings = array(), $logpreface = "" )
	{
		$this->setnames = array (
			'minor'   => array ( 'true' => "minor", 'false' => "notminor" ),
			'notminor' => array ( 'true' => "notminor", 'false' => "minor" ),
		);
		parent::__construct ( $backend, $settings, $logpreface );
	}


	# ----- Overriding ----- #


	public function nohooks__set_params ( $hook_object, $params )
	{
		$this->set_token ( $params, $this->backend->tokens->edit_token() );

		return parent::nohooks__set_params ( $hook_object, $params );
	}


	# ----- Implemented ----- #


	protected function module ( $settings )
	{
		return new API_Module_Edit ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


}
