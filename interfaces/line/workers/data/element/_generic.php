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
require_once ( dirname ( __FILE__ ) . '/../../../../../libs/misc/subelements.php' );



abstract class Worker_DataElement extends Worker_Data
{

	public $new_element_type;

	public $sublevels = NULL;  // or array with subs names as values


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Element";
	}


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );
		if ( $result === false )
			return false;

		$new_element = $this->new_element ( $signal );
		if ( is_null ( $new_element ) )
			return false;

		$new_element_type = $this->new_element_type ( $signal );
		$new_element_key  = $this->new_element_key  ( $signal );

		$extra_params = array (
			'new_element_type' => $new_element_type,
		);

		$signal->set_data_type ( $this->default_data_key, $new_element_type );
		$signal->set_data_element ( $this->default_data_key, $new_element );
		$signal->set_data_element_key ( $this->default_data_key, $new_element_key );

		$this->set_jobdata ( $result, $extra_params );

		return true;
	}


	# ----- Overridable ----- #


	protected function new_element_type ( &$signal )
	{
		return ( isset ( $this->new_element_type )
			? $this->new_element_type
			: $signal->data_element_type ( $this->default_data_key ) );
	}


	protected function new_element_key ( &$signal )
	{
		return $signal->data_element_key ( $this->default_data_key );
	}


	# ----- Abstract ----- #


	abstract protected function new_element ( &$signal );


}
