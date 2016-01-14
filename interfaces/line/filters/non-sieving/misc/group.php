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



class Filter_Group extends Filter_NonSieving
{


	public $group_size;

	public $set_original_data_key = true;

	public $elements_type;


	protected $elements;


	# ----- Constructor ----- #

	function __construct ( $core, $group_size = NULL )
	{
		parent::__construct ( $core );
		if ( ! is_null ( $group_size ) )
			$this->group_size = $group_size;
	}


	# ----- Overriding ----- #


	protected function process_start ( &$signal )
	{
		$this->elements = array();
		return true;
	}


	protected function process_data ( &$signal )
	{
		if ( ! isset ( $this->elements_type ) )
			$this->elements_type = $signal->data_type ( $this->default_data_key );

		if ( parent::process_data ( $signal ) )
			if ( count ( $this->elements ) < $this->group_size )
			{
				$data_element = $signal->data_element ( $this->default_data_key );
				if ( $this->set_original_data_key )
					$this->elements[$signal->data_element_key ( $this->default_data_key )] =
						$data_element;
				else
					$this->elements[] = $data_element;

				return false;
			}
			else
			{
				$signal->set_data_element ( $this->default_data_key, $this->elements );
				$signal->set_data_type ( $this->default_data_key, $this->elements_type );
				$this->elements = array();
				return true;
			}
		else
			return false;
	}


	protected function process_end ( &$signal )
	{
		if ( count ( $this->elements ) )
		{
			$new_signal = new LineSignal_Data ( $this->elements, $this->elements_type );
			$this->propagate_signal ( $new_signal );
		}
		return true;
	}


	# ----- Implemented ----- #


	protected function job_params ()
	{
		return array ( 'group_size' => $this->group_size );
	}


	protected function signal_log_slot_name ()
	{
		return "Group";
	}


}
