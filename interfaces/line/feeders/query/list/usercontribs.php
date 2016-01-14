<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Usercontribs List feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Feeder_Query_List_Usercontribs extends Feeder_Query_List
{


	public $set_user_from_signal_data = true;


	# ----- Overriding ----- #


	protected function set_slot_params_from_signal ( &$signal )
	{
		if ( ( $this->set_user_from_signal_data ) &&
			( $signal instanceof LineSignal_Data ) )
		{
			$user = $signal->data_user ( $this->default_data_key );
			if ( ! is_null ( $user ) )
				$this->user = $user;
		}

		return parent::set_slot_params_from_signal ( $signal );
	}


	# ----- Instantiating ----- #

	protected function data_type ()
	{
		return "array/usercontrib";
	}


	protected function query ( &$core )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../../../../core/queries/list/usercontribs.php' );
		return new Query_List_Usercontribs ( $core );
	}


}
