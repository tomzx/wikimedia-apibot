<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Set param: From param class
#  (Propagates also the Set param class for children that don't need a From)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Worker_SetParam_FromParam extends Worker_SetParam
{

	public $from_group;
	public $from_name;


	# ----- Overriding ----- #


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'from_name' );
		$this->_get_param ( $params, 'from_group' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'from_name' );
		$this->_set_param ( $params, 'from_group' );

		return parent::set_params ( $params );
	}


	# ----- Implemented ----- #


	protected function new_paramvalue ( &$signal )
	{
		if ( ! isset ( $this->from_group ) )
		{
			$this->log ( 'object $from_group not set - using $group instead',
				LL_WARNING );
			$this->from_group = $this->group;
		}
		if ( ! $signal->exists_paramgroup ( $this->from_group ) )
		{
			$this->log ( 'paramgroup ' . $this->from_group .
				' does not exist - cannot proceed!', LL_ERROR );
			return NULL;
		}

		if ( ! isset ( $this->from_name ) )
		{
			$this->log ( 'object $from_name not set - using $name instead',
				LL_WARNING );
			$this->from_name = $this->name;
		}
		if ( ! $signal->exists_param ( $this->from_group, $this->from_name ) )
		{
			$this->log ( 'param ' . $this->from_group . '::' . $this->from_name .
				' does not exist - cannot proceed!', LL_ERROR );
			return NULL;
		}

		$paramvalue = $signal->param ( $group, $name );
		if ( is_null ( $paramvalue ) )
			return NULL;
		else
			return $this->check_and_modify_paramvalue ( $paramvalue );
	}


	# ----- Abstract ----- #


	abstract protected function check_and_modify_paramvalue ( $from_value );


}
