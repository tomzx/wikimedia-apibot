<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: Generic List.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );


abstract class API_Query_List extends API_Query
{

	# ----- Overriding ----- #

	public function nohooks__is_paramname_ok ( $hook_object, $name )
	{
		return ( parent::nohooks__is_paramname_ok ( $hook_object, $name ) ||
			$this->is_list_paramname_ok ( $name ) );
	}

	public function nohooks__is_paramvalue_ok ( $hook_object,
		$name, $value, $setmode = NULL )
	{
		return ( parent::nohooks__is_paramname_ok ( $hook_object, $name ) ||
			$this->is_list_paramvalue_ok ( $name, $value, $setmode ) );
	}


	public function nohooks__set_params ( $hook_object, $params )
	{
		$listname = $this->queryname();
		$paramnames = $this->list_paramnames();

		if ( ! isset ( $params['_list'] ) )
			$params['_list'] = array();
		if ( ! isset ( $params['_list'][$listname] ) )
			$params['_list'][$listname] = array();

		foreach ( $paramnames as $paramname )
		{
			if ( isset ( $params[$paramname] ) )
			{
				if ( ! isset ( $params['_list'][$listname][$paramname] ) )
					$params['_list'][$listname][$paramname] = $params[$paramname];
				unset ( $params[$paramname] );
			}

			if ( isset ( $this->$paramname ) &&
				! isset ( $params['_list'][$listname][$paramname] ) )

				$params['_list'][$listname][$paramname] = $this->$paramname;
		}

		return parent::nohooks__set_params ( $hook_object, $params );
	}


	# ----- Implemented ----- #


	protected function querykey ()
	{
		return $this->queryname();
	}


	protected function querytype ()
	{
		return '_list';
	}


	# ----- Overridable ----- #


	protected function list_paramnames ()
	{
		return $this->backend->info->querymodule_paramnames ( $this->queryname() );
	}

	protected function is_list_paramname_ok ( $name )
	{
		return $this->action->is_list_paramname_ok ( $this->queryname(), $name );
	}

	protected function is_list_paramvalue_ok ( $name, $value, $setmode = NULL )
	{
		return $this->action->is_list_paramvalue_ok ( $this->queryname(),
			$name, $value, $setmode );
	}


}
