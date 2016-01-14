<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic filter checker with callback class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_check.php' );


abstract class Checker_WithCallback extends Checker
{

	protected $element_check_callback;


	# ----- Constructor ----- #

	function __construct ( $params, $element_check_callback )
	{
		$this->element_check_callback = $element_check_callback;
		parent::__construct ( $params );
	}


	# ----- Tools ----- #

	protected function check_element ( $element, $param )
	{
		return call_user_func ( $this->element_check_callback, $element, $param );
	}


}
