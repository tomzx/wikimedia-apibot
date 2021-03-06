<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Alltransclusions List feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Feeder_Query_List_Alltransclusions extends Feeder_Query_List
{


	public $set_nsid_from_signal_data = true;


	# ----- Overriding ----- #


	protected function set_slot_params_from_signal ( &$signal )
	{
		if ( ( $this->set_nsid_from_signal_data ) &&
			( $signal instanceof LineSignal_Data ) )
		{
			$nsid = $signal->data_nsid ( $this->default_data_key );
			if ( ! is_null ( $nsid ) )
				$this->namespace = $nsid;
		}

		return parent::set_slot_params_from_signal ( $signal );
	}


	# ----- Instantiating ----- #

	protected function data_type ()
	{
		return "array/page";
	}


	protected function query ( &$core )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../../../../core/queries/list/alltransclusions.php' );
		return new Query_List_Alltransclusions ( $core );
	}


}