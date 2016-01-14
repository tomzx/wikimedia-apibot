<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic User Filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../../fetchers/_auto/user.php' );



abstract class Filter_User extends Filter_Sieving
{


	protected $user_autofetcher;


	# ----- Constructor ----- #


	function __construct ( $core, $checker_params )
	{
		$this->user_autofetcher = new Autofetcher_User ( $core,
			"Filter " . $this->signal_log_slot_name(),
			array ( $this, "autofetcher_check" ) );

		parent::__construct ( $core, $checker_params );
	}


	# ----- Tools ----- #


	public function autofetcher_check ( &$signal )
	{
		$data_type = $signal->data_type ( $this->default_data_key );
		$result = ( strpos ( $data_type, "/user" ) === false );
		if ( $result )
		{
			$this->log ( "Element is not a user array or object - must fetch the user",
				LL_DEBUG );
		}
		else
		{
			$result = is_null ( $this->element_to_check ( $signal ) );
			if ( $result )
				$this->log ( "User aspect '" . $this->data_property .
					"' is not set - must (re-)fetch the user", LL_DEBUG );
		}
		return $result;
	}


	# ----- Instantiating ----- #


	protected function slotname_preface ()
	{
		return "User";
	}


	protected function element_id_string ( &$signal )
	{
		$data = parent::element_to_check ( $signal );

		if ( is_array ( $data ) && isset ( $data['name'] ) )
			$name = $data['name'];
		elseif ( is_object ( $data ) && isset ( $data->name ) )
			$name = $data->name;
		elseif ( is_string ( $data ) )
			$name = $data;
		else
			$name = "---unknown username (something must be wrong!)---";

		return "user '" . $name . "'";
	}


	# ----- Access point ----- #


	public function process_data ( &$signal )
	{
		if ( $this->user_autofetcher->check_and_fetch ( $signal ) )
			return parent::process_data ( $signal );
		else
			return NULL;
	}


	# $this->data_property should be set to what is being checked

}
