<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: Generic with Querymodule and Properties
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_with_querymodule.php' );


abstract class API_Query_WithProperties extends API_Query_WithQuerymodule
{

	public $properties;  // set pape properties params here


	# ----- Overriding ----- #


	public function nohooks__set_params ( $hook_object, $params )
	{
		$properties_names = $this->backend->info->available_properties();

		foreach ( $properties_names as $property_name )
		{
			if ( is_array ( $this->properties ) &&
				isset ( $this->properties[$property_name] ) )
			{
				if ( ! isset ( $params['_prop'] ) )
					$params['_prop'] = array();

				if ( ! isset ( $params['_prop'][$property_name] ) )
					$params['_prop'][$property_name] = array();

				if ( is_array ( $this->properties[$property_name] ) )
					foreach ( $this->properties[$property_name] as $paramname => $value )
						$params['_prop'][$property_name][$paramname] = $value;
				elseif ( empty ( $params['_prop'][$property_name] ) )
					$params['_prop'][$property_name] = $this->properties[$property_name];
			}
		}

		return parent::nohooks__set_params ( $hook_object, $params );
	}





}
