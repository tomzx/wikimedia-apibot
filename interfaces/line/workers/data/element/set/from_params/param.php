<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal data element set to a signal param value.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_DataElement_FromParam extends Worker_DataElement_FromParams
{


	public $name;


	# ----- Constructor ----- #

	public function __construct ( $core, $group = NULL, $name = NULL )
	{
		parent::__construct ( $core, $group );
		if ( ! is_null ( $name ) )
			$this->name = $name;
	}


	# ----- Overriding ----- #


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'name' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'name' );

		return parent::set_params ( $params );
	}


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Value";
	}


	protected function new_element_value ( &$signal )
	{
		$this->die_on_nonstring_property ( 'group' );
		$this->die_on_nonstring_property ( 'name' );

		return $signal->param ( $this->group, $this->name );
	}


}
