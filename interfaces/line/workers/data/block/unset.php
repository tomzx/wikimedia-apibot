<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal data block key unset worker class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataBlock_Unset extends Worker_DataBlock
{

	# ----- Implemented ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Unset";
	}


	# ----- Overriding ----- #


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$signal->unset_data_block ( $this->default_data_key );

		$this->set_jobdata ( $result );

		return $result;
	}


}
