<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Write lines in plaintext file Writer generic class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_plain.php' );


class Writer_File_Lines extends Writer_FileRecords_Plain
{

	# ----- Instantiating ----- #

	protected function element_record ( &$signal )
	{
		$element = $signal->data_element ( $this->default_data_key );
		if ( is_string ( $element ) )
		{
			return $element . "\n";
		}
		 else
		{
			$this->log ( get_class ( $this ) .
				": Cannot write to file an element that is not string", LL_ERROR );
			return NULL;
		}
	}


	# ----- Overriding ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Lines";
	}


}
