<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal custom parameter setter class - Misc - Counter
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_Counter extends Worker_DataElement_Set
{

	public $start = 0;          // will start the counter with this value
	public $end = PHP_INT_MAX;  // if counter exceeds this, will be reset to start
	public $step = 1;           // counter will increase by so much
	public $repeat = 1;         // repeat a counter value this number of times
	public $skip = 0;           // skip so many packets after repeat is done

	public $skip_before = 0;    // skip that much signals before starting counting


	protected $counter;

	protected $signals_counter = 0;

	protected $repeat_counter;

	protected $skip_counter;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Counter";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'start' );
		$this->_get_param ( $params, 'end' );
		$this->_get_param ( $params, 'step' );
		$this->_get_param ( $params, 'repeat' );
		$this->_get_param ( $params, 'skip' );
		$this->_get_param ( $params, 'skip_before' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'start' );
		$this->_set_param ( $params, 'end' );
		$this->_set_param ( $params, 'step' );
		$this->_set_param ( $params, 'repeat' );
		$this->_set_param ( $params, 'skip' );
		$this->_set_param ( $params, 'skip_before' );

		return parent::set_params ( $params );
	}


	protected function new_element_value ( &$signal )
	{
		$this->signals_counter++;

		if ( ( $this->signals_counter <= $this->skip_before ) )
			return true;

		if ( ! isset ( $this->counter ) )
			$this->counter = $this->start;

		if ( ! isset ( $this->repeat_counter ) )
			$this->repeat_counter = $this->repeat;
		if ( ! isset ( $this->skip_counter ) )
			$this->skip_counter = $this->skip;

		if ( ! $this->repeat_counter )
		{
			if ( $this->skip_counter )
			{
				$this->skip_counter -= 1;
			}
			else
			{
				$this->skip_counter = $this->skip;
				$this->repeat_counter = $this->repeat;
				$this->counter += $this->step;
			}
		}

		if ( $this->counter <= $this->end )
			if ( $this->repeat_counter )
			{
				$value = $this->counter;
				$this->repeat_counter -= 1;
			}
		else
			return false;

		return $value;
	}


}
