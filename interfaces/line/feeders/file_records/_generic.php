<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - File records generic feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Feeder_FileRecords_Generic extends Feeder
{

	public $filename;

	protected $counter = 0;

	# ----- Constructor ----- #

	function __construct ( $core, $filename )
	{
		$this->filename = $filename;
		parent::__construct ( $core );
	}


	# ----- Overriding ----- #


	public function get_params ()
	{
		$params = parent::get_params();

		if ( isset ( $this->filename ) )
			$params['filename'] = $this->filename;

		return $params;
	}


	# ----- Implemented ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".FileRecords";
	}


	protected function signal_log_job ()
	{
		return array (
			'params' => array (
				'filename' => $this->filename,
			),
		);
	}


	protected function feed_data_signals ()
	{
		if ( ! file_exists ( $this->filename ) )
		{
			$this->log ( "File not found: " . $this->filename, LL_ERROR );
			return NULL;
		}
		elseif ( ! is_readable ( $this->filename ) )
		{
			$this->log ( "Cannot read file: " . $this->filename, LL_ERROR );
			return NULL;
		}

		$fp = $this->fopen ( $this->filename, 'r' );
		if ( $fp === false )
		{
			$this->log ( "Could not open file: " . $this->filename, LL_ERROR );
			return NULL;
		}

		$result = true;
		while ( ! $this->feof ( $fp ) )
		{
			$signal = $this->data_signal (
				$this->read_file_record ( $fp ), $this->data_type(), $this->counter );
			$this->counter++;

			if ( is_null ( $this->feed_data_signal ( $signal ) ) )
				$result = false;
		}

		$this->fclose ( $fp );

		return $result;
	}


	# ----- Overridable ----- #

	protected function fopen ( $filename, $mode )
	{
		return @fopen ( $filename, $mode );
	}

	protected function fclose ( $fp )
	{
		return @fclose ( $fp );
	}

	protected function feof ( $fp )
	{
		return @feof ( $fp );
	}


	# ----- Abstract ----- #

	abstract protected function read_file_record ( $fp );


}
