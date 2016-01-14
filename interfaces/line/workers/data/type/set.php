<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal data element type setter class
#
#  Does not actually convert the data, only modifies its type marker!
#  Don't use it unless you are absolutely sure what you are doing!
#  If you want the data actually converted, extend and override process_data().
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataType_Set extends Worker_DataType
{

	public $new_type;


	# ----- Constructor ----- #

	public function __construct ( $core, $new_type = NULL )
	{
		parent::__construct ( $core );
		if ( ! is_null ( $new_type ) )
			$this->new_type = $new_type;
	}


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Set";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'new_datatype' );

		return $params;
	}


	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'new_datatype' );

		return parent::set_params ( $params );
	}


	# ----- Implemented ----- #


	protected function new_datatype ( &$signal )
	{
		return $this->new_datatype;
	}


}
