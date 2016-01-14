<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic Fetcher class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_line_slot.php' );


abstract class Fetcher extends Line_Slot
{

	protected $jobdata;  // set the work job data here

	public $backup_data_key;


	# ----- Instantiating ----- #

	protected function signal_log_slot_type ()
	{
		return "fetcher";
	}

	protected function signal_log_slot_name ()
	{
		return "Fetch";
	}


	# ----- Overriding ----- #

	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'backup_data_key' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'backup_data_key' );

		return parent::set_params ( $params );
	}


	protected function end_of_line_result ( &$signal )
	{
		return true;  // fetchers can be spawned aside from the line
	}


	# ----- Setting the data element ----- #


	protected function set_fetched_element ( &$signal, $element, $element_type,
		$element_key = NULL )
	{
		if ( isset ( $this->backup_data_key ) )
			$signal->rename_data_block ( $this->default_data_key,
				$this->backup_data_key );

		$signal->create_data_block (
			$this->default_data_key, $element, $element_type, $element_key );

		return true;
	}


}
