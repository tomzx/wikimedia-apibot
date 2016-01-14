<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Write records in plain-format file Writer generic class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_records.php' );


abstract class Writer_FileRecords_Plain extends Writer_FileRecords
{

	public $mode = "a+";


	# ----- Overriding ----- #


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'mode' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'mode' );

		return parent::set_params ( $params );
	}


	# ----- Instantiated ----- #

	protected function start_file ( $filename, $mode )
	{
		return @fopen ( $this->filename, $this->mode );
	}

	protected function write_record ( $fp, $record )
	{
		return @fwrite ( $fp, $record );
	}

	protected function end_file ( $fp )
	{
		return @fclose ( $fp );
	}


}
