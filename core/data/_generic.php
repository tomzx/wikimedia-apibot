<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Data objects classes: Generic
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../core.php' );



abstract class Dataobject
{

	protected $core;
	protected $data;


	# ---- Constructor ----- #

	function __construct ( $core, $data )
	{
		$this->core = $core;
		$this->data  = $data;
	}


	# ----- Tools ----- #

	public function data ()
	{
		return $this->data;
	}


	# ----- Magic property handling ----- #

	public function __get ( $name )
	{
		return ( isset ( $this->data[$name] ) ? $this->data[$name] : NULL );
	}

	public function __set ( $name, $value )
	{
		$this->data[$name] = $value;
	}

	public function __isset ( $name )
	{
		return isset ( $this->data[$name] );
	}

	public function __unset ( $name )
	{
		unset ( $this->data[$name] );
	}


}
