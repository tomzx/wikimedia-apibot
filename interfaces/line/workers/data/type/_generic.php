<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal data element type generic changer class
#
#  Does not actually convert the data, only modifies its type marker!
#  Don't use it unless you are absolutely sure what you are doing!
#  If you want the data actually converted, extend and override process_data().
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Worker_DataType extends Worker_Data
{


	# ----- Overriding ----- #


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$extra_params = array (
			'old_type' => $signal->data_type ( $this->default_data_key ),
			'new_type' => $this->new_datatype ( $signal ),
		);

		$signal->set_data_type ( $this->default_data_key,
			$extra_params['new_type'] );

		$this->set_jobdata ( $result, $extra_params );

		return $result;
	}


	# ----- Implemented ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Type";
	}


	# ----- Abstract ----- #


	abstract protected function new_datatype ( &$signal );


}
