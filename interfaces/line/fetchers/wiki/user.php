<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - User (by name) fetcher class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Fetcher_Wiki_User extends Fetcher_Wiki
{

	public $prop;


	# ----- Constructor ----- #

	function __construct ( $core, $fetch_properties = NULL )
	{
		$this->prop = $fetch_properties;
		parent::__construct ( $core );
	}


	# ----- Overriding ----- #


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'prop' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'prop' );

		return parent::set_params ( $params );
	}


	# ----- Instantiated ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".User";
	}


	protected function process_data ( &$signal )
	{
		parent::process_data ( $signal );

		$user = $signal->data_user ( $this->default_data_key );
		if ( is_null ( $user ) )
			return false;

		$params = array (
			'users' => $user,
			'prop' => $this->prop,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../../../core/queries/list/users.php' );

		$query = new Query_List_Users ( $this->core );
		$user = $query->go ( $params );

		$result = $this->set_fetched_data ( $signal, $user );

		$this->set_jobdata ( $result );

		return $result;
	}


	protected function element_typemark ()
	{
		return "user";
	}


}
