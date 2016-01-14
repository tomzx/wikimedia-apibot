<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Echo (just displays the element) Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Writer_Echo extends Writer_Debug
{

	public $newlined = false;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Echo";
	}


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		if ( $this->print_default )
			echo $signal->data_element ( $this->default_data_key );
		if ( $this->newlined )
			echo "\n";

		$this->set_jobdata ( $result );

		return $result;
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'newlined' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'newlined' );

		return parent::set_params ( $params );
	}


}
