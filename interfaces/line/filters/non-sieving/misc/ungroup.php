<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Filters: Groups data elements in an array of a specified size or less.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



class Filter_Ungroup extends Filter_NonSieving
{


	public $element_type;


	# ----- Overriding ----- #


	protected function process_data ( &$signal )
	{
		if ( parent::process_data ( $signal ) === false )
			return false;

		$elements = $signal->data_element ( $this->default_data_key );

		foreach ( $elements as $key => $element )
		{
			if ( isset ( $this->element_type ) )
				$element_type = $this->element_type;
			else
			{
				$element_type = $signal->data_type ( $this->default_data_key );
			}

			$new_signal = new LineSignal_Data ( $element, $element_type, $key );

			$this->propagate_signal ( $new_signal );
		}

		return true;
	}


	# ----- Implemented ----- #


	protected function signal_log_slot_name ()
	{
		return "Ungroup";
	}


	protected function job_params ()
	{
		return array();
	}


}
