<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic Signal data element worker class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../../../../../libs/misc/subelements.php' );



abstract class Worker_DataElement_Set extends Worker_DataElement
{


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Set";
	}


	# ----- Implemented ----- #


	protected function new_element ( &$signal )
	{
		$value = $this->new_element_value ( $signal );
		$element = $signal->data_element ( $this->default_data_key );
		return set_subelement ( $element, $this->sublevels, $value );
	}


	# ----- New (Checks for mandatory worker properties) ----- #


	protected function die_on_unset_property ( $name )
	{
		if ( ! isset ( $this->$name ) )
		{
			$this->log ( '$' . $name . ' property is not set - cannot work!',
				LL_PANIC );
			die();
		}
	}


	protected function die_on_nonnumeric_property ( $name )
	{
		$this->die_on_unset_property ( $name );

		if ( ! is_numeric ( $this->$name ) )
		{
			$this->log ( '$' . $name . ' property is not numeric - cannot work!',
				LL_PANIC );
			die();
		}
	}


	protected function die_on_nonstring_property ( $name )
	{
		$this->die_on_unset_property ( $name );

		if ( ! is_string ( $this->$name ) )
		{
			$this->log ( '$' . $name . ' property is not string - cannot work!',
				LL_PANIC );
			die();
		}
	}


	# ----- Abstract ----- #


	abstract protected function new_element_value ( &$signal );


}
