<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: Generic Generator.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic_with_properties.php' );


abstract class API_Query_Generator extends API_Query_WithProperties
{

	# ----- Overriding ----- #


	protected function action ()
	{
		$action = parent::action();
		$action->set_generator ( $this->queryname() );
		return $action;
	}


	public function nohooks__is_paramname_ok ( $hook_object, $name )
	{
		return ( parent::nohooks__is_paramname_ok ( $hook_object, $name ) ||
			$this->is_generator_paramname_ok ( $name ) );
	}

	public function nohooks__is_paramvalue_ok ( $hook_object,
		$name, $value, $setmode = NULL )
	{
		return ( parent::nohooks__is_paramname_ok ( $hook_object, $name ) ||
			$this->action->is_generator_paramvalue_ok ( $name, $value, $setmode ) );
	}


	public function nohooks__set_params ( $hook_object, $params )
	{
		$generatorname = $this->queryname();
		$paramnames = $this->generator_paramnames();

		if ( ! isset ( $params['_generator'] ) )
			$params['_generator'] = array();
		if ( ! isset ( $params['_generator'][$generatorname] ) )
			$params['_generator'][$generatorname] = array();

		foreach ( $paramnames as $paramname )
		{
			if ( isset ( $params[$paramname] ) )
			{
				if ( ! isset ( $params['_generator'][$generatorname][$paramname] ) )
					$params['_generator'][$generatorname][$paramname] = $params[$paramname];
				unset ( $params[$paramname] );
			}

			if ( isset ( $this->$paramname ) &&
				! isset ( $params['_generator'][$generatorname][$paramname] ) )

				$params['_generator'][$generatorname][$paramname] = $this->$paramname;
		}

		return parent::nohooks__set_params ( $hook_object, $params );
	}


	# ----- Implemented ----- #


	protected function querykey ()
	{
		return "pages";
	}


	protected function querytype ()
	{
		return '_generator';
	}


	# ----- Overridable ----- #


	protected function generator_paramnames ()
	{
		return $this->backend->info->querymodule_paramnames ( $this->queryname() );
	}

	protected function is_generator_paramname_ok ( $name )
	{
		return $this->action->is_generator_paramname_ok ( $name );
	}

	protected function is_generator_paramvalue_ok ( $name, $value, $setmode = NULL )
	{
		return $this->action->is_generator_paramvalue_ok ( $name, $value, $setmode );
	}


}
