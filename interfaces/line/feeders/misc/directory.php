<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Directory (and possibly subdirs) members feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



class Feeder_Directory extends Feeder
{

	public $path = "./";
	public $maxdepth;
	public $mindepth;  // will parse below this, but will not feed the elements!

	protected $depth = 0;
	protected $counter = 0;


	# ----- Params handling ----- #

	public function is_paramname_ok ( $name )
	{
		return ( $name === "path" || $name === "maxdepth" || $name === "mindepth" );
	}

	public function is_paramvalue_ok ( $name, $value )
	{
		return true;
	}


	public function get_params ()
	{
		$params = parent::get_params();

		if ( isset ( $this->path ) )
			$params['path'] = $this->path;
		if ( isset ( $this->mindepth ) )
			$params['mindepth'] = $this->mindepth;
		if ( isset ( $this->maxdepth ) )
			$params['maxdepth'] = $this->maxdepth;

		return $params;
	}


	# ----- Implemented ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() .
			".Misc.Directory" . ( $this->depth ? ".Level_" . $this->depth : "" );
	}

	protected function signal_log_job ()
	{
		return array (
			'params' => $this->get_params(),
		);
	}


	protected function feed_data_signals ()
	{
		if ( ! isset ( $this->path ) )
		{
			$this->log ( "Path not set", LL_ERROR );
			return NULL;
		}
		if ( ! @is_dir ( $this->path ) )
		{
			$this->log ( "Path not found: " . $this->path, LL_ERROR );
			return NULL;
		}
		if ( substr ( $this->path, -1 ) != '/' )
			$this->path .= '/';


		if ( ! isset ( $this->mindepth ) )
			$this->mindepth = 0;

		if ( ! isset ( $this->maxdepth ) )
			$this->maxdepth = 0;

		$subs = array();

		$dir = @scandir ( $this->path );
		if ( $dir === false )
			$dir = array();

		foreach ( $dir as $filename )
		{
			$pathname = $this->path . $filename;
			if ( @is_dir ( $pathname ) &&
				! ( ( $filename == "." ) || ( $filename == ".." ) ) )

				$subs[] = $pathname;

			$data_signal = $this->data_signal (
				substr ( $pathname, 2 ), $this->data_type(), $this->counter );
			$this->counter++;

			if ( is_null ( $this->feed_data_signal ( $data_signal ) ) )
				return false;
		}

		if ( $this->depth < $this->maxdepth )
			foreach ( $subs as $sub )
			{
				$feeder = clone $this;
				$feeder->path = $sub;
				$feeder->depth = $this->depth + 1;
				$feeder->feed();
			}

	}


	protected function data_type ()
	{
		return "string/filename";
	}


	# ----- Overriding ----- #

	public function feed_data_signal ( &$signal )
	{
		if ( $this->depth >= $this->mindepth )
			return parent::feed_data_signal ( $signal );
		return true;
	}


}
