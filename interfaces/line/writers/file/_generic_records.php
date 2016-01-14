<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Write records in file Writer generic class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


abstract class Writer_FileRecords extends Writer_File
{


	public $filename;

	protected $fp;


	# ----- Constructor ----- #

	function __construct ( $core, $filename = NULL )
	{
		parent::__construct ( $core );
		if ( ! is_null ( $filename ) )
			$this->filename = $filename;
	}


	# ----- Overriding ----- #


	protected function process_start ( &$signal )
	{
		if ( isset ( $this->fp ) )
			return true;

		if ( $this->fp = $this->start_file ( $this->filename, $this->mode ) )
			return true;
		else
			return false;
	}

	protected function process_end ( &$signal )
	{
		if ( ! isset ( $this->fp ) )
			return true;

		if ( $this->end_file ( $this->fp ) )
		{
			unset ( $this->fp );
			return true;
		}
		else
			return false;
	}


	protected function process_data ( &$signal )
	{
		parent::process_data ( $signal );

		$record = $this->element_record ( $signal );
		if ( is_null ( $record ) )
			return NULL;
		else
			$result = $this->write_record ( $this->fp, $record );

		$this->set_jobdata ( $result );

		return ( $result !== false );
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'filename' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'filename' );

		return parent::set_params ( $params );
	}


	# ----- Abstract ----- #

	abstract protected function element_record ( &$signal );

	abstract protected function start_file ( $filename, $mode );
	abstract protected function write_record ( $fp, $record );
	abstract protected function end_file ( $fp );


}
