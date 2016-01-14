<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Set signal param class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Worker_Param extends Worker_Params
{


	public $group;
	public $name;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Param";
	}


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );
		if ( $result === false )
			return false;

		if ( ! isset ( $this->group ) )
		{
			$this->log ( 'object $group property not set - cannot work!', LL_PANIC );
			die();
		}

		if ( ! isset ( $this->name ) )
		{
			$this->log ( 'object $name property not set - cannot work!', LL_PANIC );
			die();
		}

		$result = $this->new_param ( $signal );
		if ( is_null ( $result ) )
			return false;

		$this->set_jobdata ( $result );

		return $result;
	}


	# ----- Abstract ----- #


	abstract protected function new_param ( &$signal );


}
