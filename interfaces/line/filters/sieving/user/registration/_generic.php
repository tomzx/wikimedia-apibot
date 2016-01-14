<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - User registrations generic filter class
#
#  Does not use the checker object extension!
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );


abstract class Filter_User_Registration extends Filter_User
{


	# ----- Constructor ----- #


	function __construct ( $core, $checker_params = NULL,
		$fetch_user_properties = NULL )
	{
		$this->data_property = "registration";
		parent::__construct ( $core, $checker_params, $fetch_user_properties );
	}


	# ----- Instantiating ----- #


	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".Registration";
	}


}
