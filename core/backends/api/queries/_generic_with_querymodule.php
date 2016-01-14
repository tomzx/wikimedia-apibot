<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: Generic with Querymodule
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


abstract class API_Query_WithQuerymodule extends API_Query
{


	# ----- Overriding ----- #


	public function nohooks__set_params ( $hook_object, $params )
	{
		$querytype = $this->querytype();
		$queryname = $this->queryname();
		$paramnames = $this->querymodule_paramnames ( $queryname );

		if ( is_array ( $paramnames ) )
		{

			if ( ! isset ( $params[$querytype] ) )
				$params[$querytype] = array();
			if ( ! isset ( $params[$querytype][$queryname] ) )
				$params[$querytype][$queryname] = array();

			foreach ( $paramnames as $paramname )
			{
				if ( isset ( $params[$paramname] ) )
				{
					if ( ! isset ( $params[$querytype][$queryname][$paramname] ) )
						$params[$querytype][$queryname][$paramname] = $params[$paramname];
					unset ( $params[$paramname] );
				}

				if ( isset ( $this->$paramname ) &&
					! isset ( $params[$querytype][$queryname][$paramname] ) )

					$params[$querytype][$queryname][$paramname] = $this->$paramname;
			}

		}

		return parent::nohooks__set_params ( $hook_object, $params );
	}


	# ----- New ----- #


	protected function querymodule_paramnames ( $queryname )
	{
		return $this->backend->info->querymodule_paramnames ( $queryname );
	}


	# ----- Abstract ----- #


	abstract protected function querytype ();


}
