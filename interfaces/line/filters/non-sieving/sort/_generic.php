<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Filters: Sort passing data: Generic (by Signal->data_unique_id())
#
#  Override id_string() to create your own criteria for uniqueness.
#
#  WARNING: Can hog a LOT of memory - be careful what feed you run it over!
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Filter_Sort extends Filter_NonSieving
{

	public $reverse = false;  // set to true for reverse sort

	protected $data_signals;
	protected $start_signal;


	# ----- Overriding ----- #

	protected function process_start ( &$signal )
	{
		$result = parent::process_start ( $signal );

		$this->data_signals = array();
		$this->start_signal = $signal;

		return $result;
	}


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$sortkey = $this->sortkey ( $signal );
		if ( is_null ( $sortkey ) )
			return false;

		$this->data_signals[$sortkey] = $signal;

		return $result;
	}


	protected function process_end ( &$signal )
	{
		if ( $this->reverse )
			krsort ( $this->data_signals );
		else
			ksort ( $this->data_signals );

		if ( ! $this->propagate_signal ( $this->start_signal ) )
		{
			$this->log ( "Filter " . $this->signal_log_slot_name() .
				": Start signal failed - exitting!", LL_ERROR );
			return false;
		}

		foreach ( $this->data_signals as $data_signal )
			if ( ! $this->propagate_signal ( $data_signal ) )
			{
				$this->log ( "Filter " . $this->signal_log_slot_name() .
					": Data signal failed - exitting!", LL_ERROR );
				return false;
			}

		if ( ! $this->propagate_signal ( $signal ) )
		{
			$this->log ( "Filter " . $this->signal_log_slot_name() .
				": End signal failed - exitting!", LL_ERROR );
			return false;
		}

		return true;
	}


	# ----- Overriding ----- #


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'reverse' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'reverse' );

		return parent::set_params ( $params );
	}


	# ----- Implemented ----- #

	protected function signal_log_slot_name ()
	{
		return "Sort";
	}


	protected function job_params ()
	{
		return NULL;
	}


	# ----- Abstract ----- #

	abstract protected function sortkey ( &$signal );


}
