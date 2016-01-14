<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Wikifiles storage file versions feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Feeder_WikifilesStorage_FileVersions extends Feeder_WikifilesStorage
{


	public $file;

	public $dir = "older";


	# ----- Overriding ----- #


	protected function get_params ()
	{
		$params = parent::get_params();

		$params['file'] = $this->file;
		$params['dir'] = $this->dir;

		return $params;
	}


	protected function set_params ( $params )
	{
		parent::set_params ( $params );

		if ( isset ( $params['file'] ) )
			$this->file = $params['file'];
		if ( isset ( $params['dir'] ) )
			$this->dir = $params['dir'];
	}


	# ----- Implemented ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".FileVersions";
	}


	protected function feed_data_signals ()
	{
		if ( empty ( $this->file ) )
		{
			$this->log ( $this->signal_log_slot_name() . " 'file' parameter is empty!",
				LL_WARNING );
			return false;
		}

		$versions = $this->wfstorage->file_versions ( $this->file );

		if ( $this->dir == "older" )
			krsort ( $versions );
		elseif ( $this->dir == "newer" )
			ksort ( $versions );
		else
		{
			$this->log ( "Bad value to " . $this->signal_log_slot_name() .
				" 'dir' parameter - exitting!", LL_PANIC );
			die();
		}

		foreach ( $versions as $version )
		{
			$data_signal = $this->data_signal ( $version, $this->data_type() );

			if ( is_null ( $this->feed_data_signal ( $data_signal ) ) )
				return false;
		}

		return true;
	}


	protected function data_type ()
	{
		return "string/filename";
	}


}
