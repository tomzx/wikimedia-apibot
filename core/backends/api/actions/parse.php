<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Action: Parse.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/parse.php' );


class API_Action_Parse extends API_Action
{

	public $text;
	public $title;

	public $prop;  // takes an array or bar-separated list

	public $pst;
	public $uselang;


	# ----- Implemented ----- #


	protected function module ( $settings )
	{
		return new API_Module_Parse ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


}
