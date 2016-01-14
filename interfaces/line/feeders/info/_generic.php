<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Info-based feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );


class ApibotException_CannotFeedInfo extends ApibotException_TaskClose
{
}


abstract class Feeder_Info extends Feeder
{

	# ----- Implemented ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Info";
	}


	protected function signal_log_job ()
	{
		return array (
			'params' => array (
				'wiki' => $this->core->info->site_name(),
				'user' => $this->core->info->user_name(),
			),
		);
	}


	protected function feed_data_signals ()
	{
		$elements_array = $this->info_elements_array();
		if ( is_array ( $elements_array ) )
		{
			foreach ( $elements_array as $key => $element )
			{
				$signal = $this->data_signal (
					$this->data_to_be_fed ( $element ), $this->data_type(), $key );
				if ( is_null ( $this->feed_data_signal ( $signal ) ) )
					return false;
			}
		}
		else
		{
			throw ApibotException_CannotFeedInfo (  // todo! maybe specify the elements
				'infomissing',
				"Info elements to be fed are not present"
			);
		}
		return true;
	}


	protected function data_to_be_fed ( $info_element )
	{  // override on need
		return $info_element;
	}


	# ----- Abstract ----- #

	abstract protected function info_elements_array();


}
