<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - print_r (just displays the element) Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Writer_PrintR extends Writer_Debug
{

	# ----- Implemented ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".PrintR";
	}


	# ----- Overriding ----- #


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		if ( $this->print_default )
			print_r ( $signal->data_element ( $this->default_data_key ) );

		if ( $this->print_data )
		{
			echo "Data: ";
			print_r ( $signal->all_data() );
		}

		if ( $this->print_params )
		{
			echo "Signal params: ";
			print_r ( $signal->params );
		}

		if ( $this->print_log )
		{
			echo "Log: ";
			print_r ( $signal->log );
		}

		$this->set_jobdata ( $result );

		return $result;
	}


}
