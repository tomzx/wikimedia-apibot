<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - var_dump (just displays the element) Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Writer_VarDump extends Writer_Debug
{

	# ----- Implemented ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".VarDump";
	}


	# ----- Overriding ----- #


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		if ( $this->print_default )
			var_dump ( $signal->data_element ( $this->default_data_key ) );

		if ( $this->print_data )
		{
			echo "Data: ";
			var_dump ( $signal->all_data() );
		}

		if ( $this->print_params )
		{
			echo "Extra params: ";
			var_dump ( $signal->params );
		}

		if ( $this->print_log )
		{
			echo "Log: ";
			var_dump ( $signal->log );
		}

		$this->set_jobdata ( $result );

		return $result;
	}


}
