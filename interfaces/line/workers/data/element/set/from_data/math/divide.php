<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal custom data element setter class - Math - Divide
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_Math_Divide extends Worker_DataElement_Math
{


	public $divide;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Divide";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'divide' );

		return $params;
	}


	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'divide' );

		return parent::set_params ( $params );
	}


	# ----- Implemented ----- #


	protected function modify_element ( $element )
	{
		$this->die_on_nonnumeric_property ( 'divide' );

		if ( $this->divide === 0 )
		{
			$this->log ( 'The object $divide property is zero - cannot work!',
				LL_PANIC );
			die();
		}

		return $element / $this->divide;
	}


}
