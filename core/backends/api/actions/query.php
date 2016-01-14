<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Action: Query (with querymodules).
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/query_with_modules.php' );


class API_Action_Query extends API_Action
{

	public $redirect;
	public $indexpageids;
	public $export;
	public $exportnowrap;
	public $converttitles;
	public $iwurl;


	# ----- Implemented ----- #


	protected function module ( $settings )
	{
		return new API_Module_Query_With_Modules ( $this->backend->exchanger,
			$this->backend->info, $this->backend->hooks, $settings );
	}


	# ----- Overriding ----- #


	public function nohooks__set_params ( $hook_object, $params )
	{
		$extensions =
			array ( "_generator", "_property", "_list", "_meta", "_pageset" );
		foreach ( $extensions as $extension )
			if ( isset ( $this->$extension ) )
				$params[$extension] = $this->$extension;

		foreach ( $params as $name => $sub )
			if ( ( substr ( $name, 0, 1 ) == "_" ) && isset ( $this->setnames[$name] ) )
				if ( ( $name == "_generator" ) || ( $name == "_pageset" ) )
					$params[$name] = $this->translate_params ( $sub,
						$this->setnames[$name] );
				else
					foreach ( $params[$name] as $subname => $sub )
						$params[$name][$subname] = $this->translate_params ( $sub,
							$this->setnames[$name][$subname] );

		return parent::nohooks__set_params ( $hook_object, $params );
	}


	# ----- Xfer cycling ----- #


	public function nohooks__next ( $hook_object )
	{
		$result = $this->module->next();
		$this->data = &$this->module->data;
		return $result;
	}


	public function next ()
	{
		return $this->backend->hooks->call (
			get_class ( $this ) . '::next',
			array ( $this, 'nohooks__next' ),
			$this
		);
	}


	# ----- Data access ----- #


	# --- Area elements --- #


	public function query_area_elements_count ( $area_key )
	{
		return $this->module->query_area_elements_count ( $area_key );
	}

	public function query_area_elements_keys ( $area_key )
	{
		return $this->module->query_area_elements_keys ( $area_key );
	}

	public function query_area_element ( $area_key, $element_key )
	{
		return $this->module->query_area_element ( $element_key, $area_key );
	}


	public function query_area_first_element ( $area_key )
	{
		return $this->module->query_area_first_element ( $area_key );
	}

	public function query_area_next_element ( $area_key )
	{
		return $this->module->query_area_next_element ( $area_key );
	}

	public function query_area_last_element ( $area_key )
	{
		return $this->module->query_area_last_element ( $area_key );
	}


	# ----- Generator params handling ----- #


	public function set_generator ( $name )
	{
		return $this->module->set_generator ( $name );
	}

	public function is_generator_paramname_ok ( $name )
	{
		return $this->module->is_generator_paramname_ok ( $name );
	}

	public function is_generator_param_under_limit ( $name )
	{
		return $this->module->is_generator_param_under_limit ( $name );
	}

	public function is_generator_paramvalue_ok ( $name, $value, $setmode = NULL )
	{
		return $this->module->is_generator_paramvalue_ok ( $name, $value,
			$setmode );
	}

	public function generator_param_isset ( $name )
	{
		return $this->module->generator_param_isset ( $name );
	}

	public function get_generator_param ( $name )
	{
		return $this->module->get_generator_param ( $name );
	}

	public function get_generator_params ()
	{
		return $this->module->get_generator_params();
	}

	public function set_generator_param ( $name, $value )
	{
		return $this->module->set_generator_param ( $name, $value );
	}

	public function set_generator_params ( $params_array )
	{
		return $this->module->set_generator_params ( $params_array );
	}

	public function clear_generator_param ( $name, $value = NULL )
	{
		return $this->module->clear_generator_param ( $name, $value );
	}

	public function clear_generator_params ()
	{
		return $this->module->clear_generator_params ();
	}


	# ----- Properties params handling ----- #


	public function is_property_ok ( $property )
	{
		return $this->module->is_property_ok ( $property );
	}

	public function add_property ( $property )
	{
		return $this->module->add_property ( $property );
	}

	public function del_property ( $property )
	{
		return $this->module->del_property ( $property );
	}

	public function is_property_paramname_ok ( $property, $name )
	{
		return $this->module->is_property_paramname_ok ( $property, $name );
	}

	public function is_property_param_under_limit ( $property, $name )
	{
		return $this->module->is_property_param_under_limit ( $property, $name );
	}

	public function is_property_paramvalue_ok ( $property, $name, $value,
		$setmode = NULL )
	{
		return $this->module->is_property_paramvalue_ok ( $property, $name, $value,
			$setmode );
	}

	public function property_param_isset ( $property, $name )
	{
		return $this->module->property_param_isset ( $property, $name );
	}

	public function get_property_param ( $property, $name )
	{
		return $this->module->get_property_param ( $property, $name );
	}

	public function get_property_params ( $property )
	{
		return $this->module->get_property_params ( $property );
	}

	public function set_property_param ( $property, $name, $value )
	{
		return $this->module->set_property_param ( $property, $name, $value );
	}

	public function set_property_params ( $property, $params_array )
	{
		return $this->module->set_property_params ( $property, $params_array );
	}

	public function set_properties_params ( $properties_array )
	{
		return $this->module->set_properties_params ( $properties_array );
	}

	public function clear_property_param ( $property, $name, $value = NULL )
	{
		return $this->module->clear_property_param ( $property, $name, $value );
	}

	public function clear_property_params ( $property )
	{
		return $this->module->clear_property_params ( $property );
	}

	public function clear_properties_params ()
	{
		return $this->module->clear_properties_params ();
	}


	# ----- Lists params handling ----- #


	public function add_list ( $name )
	{
		return $this->module->add_list ( $name );
	}

	public function del_list ( $name )
	{
		return $this->module->del_list ( $name );
	}

	public function is_list_paramname_ok ( $list, $name )
	{
		return $this->module->is_list_paramname_ok ( $list, $name );
	}

	public function is_list_param_under_limit ( $list, $name )
	{
		return $this->module->is_list_param_under_limit ( $list, $name );
	}

	public function is_list_paramvalue_ok ( $list, $name, $value,
		$setmode = NULL )
	{
		return $this->module->is_list_paramvalue_ok ( $list, $name, $value,
			$setmode );
	}

	public function list_param_isset ( $list, $name )
	{
		return $this->module->list_param_isset ( $list, $name );
	}

	public function get_list_param ( $list, $name )
	{
		return $this->module->get_list_param ( $list, $name );
	}

	public function get_list_params ( $list )
	{
		return $this->module->get_list_params ( $list );
	}

	public function set_list_param ( $list, $name, $value )
	{
		return $this->module->set_list_param ( $list, $name, $value );
	}

	public function set_list_params ( $list, $params_array )
	{
		return $this->module->set_list_params ( $list, $params_array );
	}

	public function clear_list_param ( $list, $name, $value = NULL )
	{
		return $this->module->clear_list_param ( $list, $name, $value );
	}

	public function clear_list_params ( $list )
	{
		return $this->module->clear_list_params ( $list );
	}

	public function clear_lists_params ()
	{
		return $this->module->clear_lists_params ();
	}


	# ----- Meta params handling ----- #


	public function add_meta ( $name )
	{
		return $this->module->add_meta ( $name );
	}

	public function del_meta ( $name )
	{
		return $this->module->del_meta ( $name );
	}

	public function is_meta_paramname_ok ( $meta, $name )
	{
		return $this->module->is_meta_paramname_ok ( $meta, $name );
	}

	public function is_meta_param_under_limit ( $meta, $name )
	{
		return $this->module->is_meta_param_under_limit ( $meta, $name );
	}

	public function is_meta_paramvalue_ok ( $meta, $name, $value,
		$setmode = NULL )
	{
		return $this->module->is_meta_paramvalue_ok ( $meta, $name, $value,
			$setmode );
	}

	public function meta_param_isset ( $meta, $name )
	{
		return $this->module->meta_param_isset ( $meta, $name );
	}

	public function get_meta_param ( $meta, $name )
	{
		return $this->module->get_meta_param ( $meta, $name );
	}

	public function get_meta_params ( $meta )
	{
		return $this->module->get_meta_params ( $meta );
	}

	public function set_meta_param ( $meta, $name, $value )
	{
		return $this->module->set_meta_param ( $meta, $name, $value );
	}

	public function set_meta_params ( $meta, $params_array )
	{
		return $this->module->set_meta_params ( $meta, $params_array );
	}

	public function clear_meta_param ( $meta, $name, $value = NULL )
	{
		return $this->module->clear_meta_param ( $meta, $name, $value );
	}

	public function clear_meta_params ( $meta )
	{
		return $this->module->clear_meta_params ( $meta );
	}

	public function clear_metas_params ()
	{
		return $this->module->clear_metas_params ();
	}


	# ----- Pageset module params handling ----- #


	public function is_pageset_paramname_ok ( $name )
	{
		return $this->module->is_pageset_paramname_ok ( $name );
	}

	public function is_pageset_param_under_limit ( $name )
	{
		return $this->module->is_pageset_param_under_limit ( $name );
	}

	public function is_pageset_paramvalue_ok ( $name, $value, $setmode = NULL )
	{
		return $this->module->is_pageset_paramvalue_ok ( $name, $value, $setmode );
	}

	public function pageset_param_isset ( $name )
	{
		return $this->module->pageset_param_isset ( $name );
	}

	public function get_pageset_param ( $name )
	{
		return $this->module->get_pageset_param ( $name );
	}

	public function get_pageset_params ()
	{
		return $this->module->get_pageset_params();
	}

	public function set_pageset_param ( $name, $value )
	{
		return $this->module->set_pageset_param ( $name, $value );
	}

	public function set_pageset_params ( $params_array )
	{  // only one param!
		return $this->module->set_pageset_params ( $params_array );
	}

	public function clear_pageset_param ( $name, $value = NULL )
	{
		return $this->module->clear_pageset_param ( $name, $value );
	}

	public function clear_pageset_params ()
	{
		return $this->module->clear_pageset_params();
	}

	public function set_titles ( $value )
	{
		return $this->module->set_titles ( $value );
	}

	public function set_pageids ( $value )
	{
		return $this->module->set_pageids ( $value );
	}

	public function set_revids ( $value )
	{
		return $this->module->set_revids ( $value );
	}

	public function titles_isset ()
	{
		return $this->module->titles_isset();
	}

	public function pageids_isset ()
	{
		return $this->module->pageids_isset();
	}

	public function revids_isset ()
	{
		return $this->module->revids_isset();
	}

	public function clear_titles ( $value = NULL )
	{
		return $this->module->clear_titles ( $value );
	}

	public function clear_pageids ( $value = NULL )
	{
		return $this->module->clear_pageids ( $value );
	}

	public function clear_revids ( $value = NULL )
	{
		return $this->module->clear_revids ( $value );
	}


}
