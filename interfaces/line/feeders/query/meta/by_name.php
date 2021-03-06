<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Query Meta from info feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Feeder_Query_Meta_ByName extends Feeder_Query_Meta
{

	protected $modulename;
	protected $data_type;


	# ----- Constructor ----- #

	function __construct ( $core, $modulename, $data_type = "" )
	{
		$this->modulename = $modulename;
		$this->data_type = $data_type;
		parent::__construct ( $core );
	}


	# ----- Instantiating ----- #

	protected function data_type ()
	{
		return $this->data_type;
	}


	protected function query ( &$core )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../../../../core/queries/meta/by_name.php' );
		return new Query_Meta_ByName ( $this->modulename, $this->core );
	}


}
