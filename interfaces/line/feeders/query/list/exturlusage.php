<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Exturlusage List feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Feeder_Query_List_Exturlusage extends Feeder_Query_List
{


	public $set_extlink_from_signal_data = true;


	# ----- Overriding ----- #


	protected function set_slot_params_from_signal ( &$signal )
	{
		if ( ( $this->set_extlink_from_signal_data ) &&
			( $signal instanceof LineSignal_Data ) )
		{
			$extlink = $signal->data_extlink ( $this->default_data_key );
			if ( ! is_null ( $extlink ) )
			{
				$extlink = preg_replace ( '^.+\:(\/\/)', '', $extlink );
				$this->query = $extlink;
			}
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
			'/../../../../../core/queries/list/exturlusage.php' );
		return new Query_List_Exturlusage ( $core );
	}


}
