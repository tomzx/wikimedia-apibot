<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Param: General: Rename class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



class Worker_Param_Rename extends Worker_Param_General
{

	public $new_name;
	public $new_group;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Rename";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'new_name' );
		$this->_get_param ( $params, 'new_group' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'new_name' );
		$this->_set_param ( $params, 'new_group' );

		return parent::set_params ( $params );
	}


	# ----- Implemented ----- #


	protected function new_param ( &$signal )
	{
		if ( ! isset ( $this->new_group ) )
		{
			$this->log ( '$new_group property not set - cannot work!',
				LL_PANIC );
			die();
		}

		if ( ! isset ( $this->new_name ) )
		{
			$this->log ( '$new_name property not set - cannot work!',
				LL_PANIC );
			die();
		}

		return $signal->rename_param ( $this->group, $this->name,
			$this->new_name, $this->new_group );
	}


}
