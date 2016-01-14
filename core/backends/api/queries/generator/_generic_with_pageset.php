<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: Generic Generator of a Property type.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


abstract class API_Query_Generator_WithPageset extends API_Query_Generator
{

	# ----- Overriding ----- #


	public function nohooks__is_paramname_ok ( $hook_object, $name )
	{
		return ( parent::is_paramname_ok ( $name ) ||
			$this->is_pageset_paramname_ok ( $name ) );
	}

	public function nohooks__is_paramvalue_ok ( $hook_object,
		$name, $value, $setmode = NULL )
	{
		return ( parent::is_paramvalue_ok ( $name, $value, $setmode ) ||
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


	# ----- Overridable ----- #


	protected function pageset_paramnames ()
	{
		return $this->backend->info->pagesetmodule_paramnames();
	}


	protected function is_pageset_paramname_ok ( $name )
	{
		$this->action->is_pageset_paramname_ok ( $name ) );
	}


	protected function is_pageset_paramvalue_ok ( $name, $value, $setmode = NULL )
	{
		return $this->action->is_pageset_paramvalue_ok ( $name, $value, $setmode );
	}


}
