<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: Generic.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../../_generic/query.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/query.php' );


abstract class API_Query extends Backend_Query
{


	# ----- Implemented ----- #

	protected function action ( $settings, $defaults )
	{
		return new API_Action_Query ( $this->backend, $settings, $defaults );
	}


	protected function backend_name ()
	{
		return "api";
	}


	public function nohooks__set_params ( $hook_object, $params )
	{
		$paramnames = $this->universal_query_paramnames();
		foreach ( $paramnames as $paramname )
			if ( isset ( $this->$paramname ) && ! isset ( $params[$paramname] ) )
				$params[$paramname] = $this->$paramname;

		return parent::nohooks__set_params ( $hook_object, $params );
	}


	# ----- Overridable ----- #


	protected function universal_query_paramnames ()
	{
		return $this->backend->info->universal_query_paramnames();
	}


}
