<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Param: General: Swap class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



class Worker_Param_Swap extends Worker_Param_General
{


	public $group2;
	public $name2;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Swap";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'name2' );
		$this->_get_param ( $params, 'group2' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'name2' );
		$this->_set_param ( $params, 'group2' );

		return parent::set_params ( $params );
	}


	# ----- Implemented ----- #


	protected function new_param ( &$signal )
	{
		if ( ! isset ( $this->group2 ) )
		{
			$this->log ( '$group2 property not set - cannot work!',
				LL_PANIC );
			die();
		}

		if ( ! isset ( $this->name2 ) )
		{
			$this->log ( '$name2 property not set - cannot work!',
				LL_PANIC );
			die();
		}

		return $signal->swap_params ( $this->group, $this->name,
			$this->group2, $this->name2 );
	}


}
