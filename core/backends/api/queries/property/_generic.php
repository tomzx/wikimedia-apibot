<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: Generic Property.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic_with_pageset.php' );


abstract class API_Query_Property extends API_Query_WithPageset
{

	public $parent_page_key = 'parent_page';


	# ----- Overriding ----- #

	protected function results ( $result )
	{
		if ( $result )
			if ( isset ( $this->action->data['query'] ) )
				if ( isset ( $this->action->data['query']['pages'] ) )
				{
					$results = array();
					foreach ( $this->action->data['query']['pages'] as $page )
						if ( isset ( $page[$this->querykey()] ) )
							foreach ( $page[$this->querykey()] as $propkey => $property )
							{
								if ( is_array ( $property ) )
								{
									if ( $this->parent_page_key === "" ) // put all page subs as property subs (except the property itself)
									{
										foreach ( $page as $key => $value )
											if ( $key !== $this->querykey() )
												$property[$key] = $value;
									}
									elseif ( $this->parent_page_key !== NULL ) // put the page as a sub to the property
									{
										$property[$this->parent_page_key] = $page;
										unset ( $property[$this->parent_page_key][$this->querykey()] );
									}
								}
								if ( is_numeric ( $propkey ) )
									$results[] = $property;
								else
									$results[$propkey] = $property;
							}
					return $results;
				}
				else
					return array();
			else
				return NULL;
		else
			return array();
	}


	public function nohooks__is_paramname_ok ( $hook_object, $name )
	{
		return ( parent::nohooks__is_paramname_ok ( $hook_object, $name ) ||
			$this->is_property_paramname_ok ( $name ) );
	}

	public function nohooks__is_paramvalue_ok ( $hook_object,
		$name, $value, $setmode = NULL )
	{
		return ( parent::nohooks__is_paramvalue_ok ( $hook_object,
				$name, $value, $setmode ) ||
			$this->is_property_paramvalue_ok ( $name, $value, $setmode ) );
	}


	public function nohooks__set_params ( $hook_object, $params )
	{
		$propname = $this->queryname();
		$paramnames = $this->property_paramnames ( $propname );

		if ( ! isset ( $params['_prop'] ) )
			$params['_prop'] = array();
		if ( ! isset ( $params['_prop'][$propname] ) )
			$params['_prop'][$propname] = array();

		foreach ( $paramnames as $paramname )
		{
			if ( isset ( $params[$paramname] ) )
			{
				if ( ! isset ( $params['_prop'][$propname][$paramname] ) )
					$params['_prop'][$propname][$paramname] = $params[$paramname];
				unset ( $params[$paramname] );
			}

			if ( isset ( $this->$paramname ) &&
				! isset ( $params['_prop'][$propname][$paramname] ) )

				$params['_prop'][$propname][$paramname] = $this->$paramname;
		}

		if ( isset ( $params['parent_page_key'] ) )
		{
			$this->parent_page_key = $params['parent_page_key'];
			unset ( $params['parent_page_key'] );
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
		return '_prop';
	}


	# ----- Overridable ----- #


	protected function property_paramnames ()
	{
		return $this->backend->info->querymodule_paramnames ( $this->queryname() );
	}

	protected function is_property_paramname_ok ( $name )
	{
		return $this->action->is_property_paramname_ok ( $this->queryname(), $name );
	}

	protected function is_property_paramvalue_ok ( $name, $value,
		$setmode = NULL )
	{
		return $this->action->is_property_paramvalue_ok ( $this->queryname(),
			$name, $value, $setmode );
	}


}
