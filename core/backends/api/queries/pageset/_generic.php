<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: Generic Query Pageset with Properties.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic_with_properties.php' );


abstract class API_Query_Pageset extends API_Query_WithProperties
{

	# ----- Overriding ----- #

	public function nohooks__is_paramname_ok ( $hook_object, $name )
	{
		return ( parent::nohooks__is_paramname_ok ( $hook_object, $name ) ||
			$this->is_pageset_paramname_ok ( $name ) );
	}

	public function nohooks__is_paramvalue_ok ( $hook_object,
		$name, $value, $setmode = NULL )
	{
		return ( parent::nohooks__is_paramvalue_ok ( $hook_object,
				$name, $value, $setmode ) ||
			$this->is_pageset_paramvalue_ok ( $name, $value, $setmode ) );
	}


	public function nohooks__set_params ( $hook_object, $params )
	{
		$paramnames = $this->pageset_paramnames();

		if ( ! isset ( $params['_pageset'] ) )
			$params['_pageset'] = array();

		foreach ( $paramnames as $paramname )
		{
			if ( isset ( $params[$paramname] ) )
			{
				if ( ! isset ( $params['_pageset'][$paramname] ) )
					$params['_pageset'][$paramname] = $params[$paramname];
				unset ( $params[$paramname] );
			}

			if ( isset ( $this->$paramname ) &&
				! isset ( $params['_pageset'][$paramname] ) )

				$params['_pageset'][$paramname] = $this->$paramname;
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
		return '_pageset';
	}


	# ----- Overridable ----- #


	protected function pageset_paramnames ()
	{
		return $this->backend->info->pagesetmodule_paramnames();
	}

	protected function is_pageset_paramname_ok ( $name )
	{
		return $this->action->is_pageset_paramname_ok ( $name );
	}

	protected function is_pageset_paramvalue_ok ( $name, $value, $setmode = NULL )
	{
		return $this->action->is_pageset_paramvalue_ok ( $name, $value, $setmode );
	}


}
