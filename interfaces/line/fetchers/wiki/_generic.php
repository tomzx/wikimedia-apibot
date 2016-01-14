<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic wiki object fetcher class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );


abstract class Fetcher_Wiki extends Fetcher
{

	# ----- Overriding ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Wiki";
	}


	protected function set_fetched_data ( &$signal, $element, $key = NULL )
	{
		if ( is_array ( $element ) )
			return parent::set_fetched_element ( $signal, $element, "array/" . $this->element_typemark(), $key );
		elseif ( is_object ( $element ) )
			return parent::set_fetched_element ( $signal, $element, "object/" . $this->element_typemark(), $key );
		else
			return false;
	}


	# ----- Abstract ----- #


	abstract protected function element_typemark ();


}
