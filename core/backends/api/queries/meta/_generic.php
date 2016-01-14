<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: Generic Meta.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );


abstract class API_Query_Meta extends API_Query
{

	# ----- Overriding ----- #

	public function nohooks__is_paramname_ok ( $hook_object, $name )
	{
		return ( parent::nohooks__is_paramname_ok ( $hook_object, $name ) ||
			$this->is_meta_paramname_ok ( $name ) );
	}

	public function nohooks__is_paramvalue_ok ( $hook_object,
		$name, $value, $setmode = NULL )
	{
		return ( parent::nohooks__is_paramvalue_ok ( $hook_object, $name ) ||
			$this->is_meta_paramvalue_ok ( $name, $value, $setmode ) );
	}


	public function nohooks__set_params ( $hook_object, $params )
	{
		$metaname = $this->queryname();
		$paramnames = $this->meta_paramnames();

		if ( ! isset ( $params['_meta'] ) )
			$params['_meta'] = array();
		if ( ! isset ( $params['_meta'][$metaname] ) )
			$params['_meta'][$metaname] = array();

		foreach ( $paramnames as $paramname )
		{
			if ( isset ( $params[$paramname] ) )
			{
				if ( ! isset ( $params['_meta'][$metaname][$paramname] ) )
					$params['_meta'][$metaname][$paramname] = $params[$paramname];
				unset ( $params[$paramname] );
			}

			if ( isset ( $this->$paramname ) &&
				! isset ( $params['_meta'][$metaname][$paramname] ) )

				$params['_meta'][$metaname][$paramname] = $this->$paramname;
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
		return '_meta';
	}


	# ----- Overridable ----- #


	protected function meta_paramnames ()
	{
		return $this->backend->info->querymodule_paramnames ( $this->queryname() );
	}

	protected function is_meta_paramname_ok ( $name )
	{
		return $this->action->is_meta_paramname_ok ( $this->queryname(), $name );
	}

	protected function is_meta_paramvalue_ok ( $name, $value, $setmode = NULL )
	{
		return $this->action->is_meta_paramvalue_ok ( $this->queryname(),
			$name, $value, $setmode );
	}


}
