<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Array values feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



class Feeder_Array extends Feeder
{

	public $array = array();

	public $datatype;


	# ----- Implemented ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Misc.Array";
	}


	protected function signal_log_job ()
	{
		return array (
			'params' => $this->get_params(),
		);
	}


	protected function feed_data_signals ()
	{
		if ( ! isset ( $this->datatype ) )
		{
			$this->log ( "Datatype not set", LL_ERROR );
			return NULL;
		}

		foreach ( $this->array as $key => $value )
		{
			$data_signal = $this->data_signal (
				$value, $this->data_type(), $key );

			if ( is_null ( $this->feed_data_signal ( $data_signal ) ) )
				return false;
		}

	}


	protected function data_type ()
	{
		return $this->datatype;
	}


}
