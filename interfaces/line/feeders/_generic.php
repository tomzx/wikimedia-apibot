<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_line_slot.php' );


define ( 'FEED_ON_SIGNAL_NONE', 0 );  // for disabling temporarily the feeding
define ( 'FEED_ON_SIGNAL_START', 1 );
define ( 'FEED_ON_SIGNAL_DATA', 2 );
define ( 'FEED_ON_SIGNAL_END', 3 );


abstract class Feeder extends Line_Slot
{


	protected $core;
	protected $params;

	public $signal_params;

	public $feed_on_signal = FEED_ON_SIGNAL_DATA;

	public $subfeeder_mode = false;


	# ----- Constructor ----- #


	function __construct ( $core, $start_params = array() )
	{
		parent::__construct ( $core );
		$this->set_params ( $start_params );
	}


	# ----- Overriding ----- #


	# --- General functions --- #


	protected function signal_log_slot_type ()
	{
		return "feeder";
	}


	protected function signal_log_slot_name ()
	{
		return "Feeder";
	}


	# --- Received signals processing --- #


	protected function send_feed_on_signal ( &$signal )
	{
		if ( ! $this->init_feeder ( $signal ) )
			return false;
		return $this->send_feed();
	}


	protected function process_start ( &$signal )
	{
		if ( $this->feed_on_signal == FEED_ON_SIGNAL_START )
			return $this->send_feed_on_signal ( $signal );
		return true;
	}


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );
		if ( $this->feed_on_signal == FEED_ON_SIGNAL_DATA )
			return $this->send_feed_on_signal ( $signal );

		$this->set_jobdata ( $result );

		return $result;
	}


	protected function process_end ( &$signal )
	{
		if ( $this->feed_on_signal == FEED_ON_SIGNAL_END )
			return $this->send_feed_on_signal ( $signal );
		return true;
	}


	# --- Received signals propagation --- #

	# uplink signals must not be propagated; override process_start() etc. on need
	protected function propagate_signal ( &$signal )
	{
		if ( ( ( $this->feed_on_signal == FEED_ON_SIGNAL_START ) &&
			! ( $signal instanceof LineSignal_Start ) ) ||
			( ( $this->feed_on_signal == FEED_ON_SIGNAL_DATA ) &&
			! ( $signal instanceof LineSignal_Data ) ) ||
			( ( $this->feed_on_signal == FEED_ON_SIGNAL_END ) &&
			! ( $signal instanceof LineSignal_End ) ) )

			return parent::propagate_signal ( $signal );

		return true;
	}


	# ----- Generating signals ----- #


	protected function base_signal ( $type )
	{
		return array (
			'type' => $type,
			'log'  => array(),
			'id'   => rand ( 0, PHP_INT_MAX ),
			'signal_params' => $this->signal_params,
		);
	}


	protected function start_signal ()
	{
		return new LineSignal_Start ( $this->signal_params );
	}


	protected function end_signal ()
	{
		return new LineSignal_End ( $this->signal_params ) ;
	}


	protected function data_signal ( $element, $element_type = NULL,
		$element_key = NULL )
	{
		if ( $element_type == NULL )
			$element_type = $this->data_type();

		return new LineSignal_Data ( $element, $element_type, $element_key,
			$this->signal_params );
	}


	# ----- Feed cycle ----- #


	# --- Running the feeder --- #


	protected function init_feeder ( &$signal )
	{
		return true;
	}


	protected function send_feed ()
	{
		$this->log_feeder_start();

		$start_signal = $this->start_signal();
		if ( $this->subfeeder_mode )
		{
			$this->log ( "Not sending start signal (subfeeder mode)", LL_DEBUG );
		}
		elseif ( ! parent::propagate_signal ( $start_signal ) )
		{
			$this->log ( "Feeder " . $this->signal_log_slot_name() .
				" could not start properly - exitting!", LL_ERROR );
			return false;
		}

		$result = $this->feed_data_signals();

		$end_signal = $this->end_signal();
		if ( $this->subfeeder_mode )
		{
			$this->log ( "Not sending end signal (subfeeder mode)", LL_DEBUG );
		}
		elseif ( ! parent::propagate_signal ( $end_signal ) )
		{
			$this->log ( "Feeder " . $this->signal_log_slot_name() .
				" could not end properly - exitting!", LL_ERROR );
			return false;
		}

		$this->log_feeder_end();
		return $result;
	}


	# --- Logging --- #


	protected function log_feeder_start ()
	{
		$this->log ( "Starting feeder " . $this->signal_log_slot_name(), LL_INFO );

		$params = $this->get_params();
		if ( ! empty ( $params ) )
			$this->log ( "  " . $this->core->log->stringify ( $params ), LL_DEBUG );
	}


	protected function log_feeder_end ()
	{
		$this->log ( "Feeder " . $this->signal_log_slot_name() . " ended." );
	}


	# --- Feeding data signals callback --- #

	public function feed_data_signal ( &$signal )
	{
		return parent::propagate_signal ( $signal );
	}


	# ----- Direct feed call ----- #


	public function feed ( $callback = NULL, $signal_params = NULL )
	{
		if ( ! is_null ( $callback ) )
			$this->link_with ( $callback );
		if ( ! is_null ( $signal_params ) )
			$this->signal_params = $signal_params;

		return $this->send_feed();
	}


	# ----- Abstract ----- #


	# call from it feed_data_signal() for every data element
	abstract protected function feed_data_signals ();

	abstract protected function data_type();


}
