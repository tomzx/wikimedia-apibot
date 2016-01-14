<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Query with querymodules.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/query.php' );

require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/allcategories.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/allimages.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/alllinks.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/allpages.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/alltransclusions.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/allusers.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/backlinks.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/blocks.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/categorymembers.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/deletedrevs.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/embeddedin.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/exturlusage.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/filearchive.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/imageusage.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/iwbacklinks.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/langbacklinks.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/logevents.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/protectedtitles.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/querypage.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/random.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/recentchanges.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/search.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/tags.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/usercontribs.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/users.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/watchlist.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/watchlistraw.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/lists/by_name.php' );

require_once ( dirname ( __FILE__ ) . '/../querymodules/properties/categories.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/properties/categoryinfo.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/properties/contributors.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/properties/duplicatefiles.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/properties/extlinks.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/properties/imageinfo.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/properties/images.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/properties/info.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/properties/langlinks.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/properties/links.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/properties/pageprops.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/properties/revisions.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/properties/templates.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/properties/by_name.php' );

require_once ( dirname ( __FILE__ ) . '/../querymodules/meta/siteinfo.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/meta/userinfo.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/meta/allmessages.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/meta/filerepoinfo.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/meta/globaluserinfo.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/meta/tokens.php' );
require_once ( dirname ( __FILE__ ) . '/../querymodules/meta/by_name.php' );

require_once ( dirname ( __FILE__ ) . '/../pagesetmodule/params.php' );



class API_Module_Query_With_Modules extends API_Module_Query
{

	public $convert_paramvalues = true; // auto-translate namespace names to ids etc.


	protected $substree;
	protected $psparm;


	# ----- Constructor ----- #

	function __construct ( $exchanger, $info, $hooks, $settings )
	{
		parent::__construct ( $exchanger, $info, $hooks, $settings );
		$this->clear_querymodules();
	}


	# ----- Overriding ----- #

	public function nohooks__xfer ( $hook_object )
	{
		$result = parent::nohooks__xfer ( $hook_object );
		if ( $result === false )
			$this->clear_querymodules();
		return $result;
	}

	public function nohooks__next ( $hook_object )
	{
		$result = parent::nohooks__next ( $hook_object );
		if ( $result === false )
			$this->clear_querymodules();
		return $result;
	}


	# ---------- Servicing the querymodules / pageset subs ---------- #

	# ----- Tools ----- #

	protected function save_generator_params ()
	{
		$paramobject = reset ( $this->substree['generator'] );
		if ( is_object ( $paramobject ) )
			$paramobject->save_params();
	}

	protected function restore_generator_params ()
	{
		$paramobject = reset ( $this->substree['generator'] );
		if ( is_object ( $paramobject ) )
			$paramobject->restore_params();
	}

	protected function save_substree_nogen_params ()
	{
		foreach ( $this->substree as $groupname => $array )
			if ( $groupname != 'generator' )
				foreach ( $array as $modulename => $paramobject )
					$paramobject->save_params();
	}

	protected function restore_substree_nogen_params ()
	{
		foreach ( $this->substree as $groupname => $array )
			if ( $groupname != 'generator' )
				foreach ( $array as $modulename => $paramobject )
					$paramobject->restore_params();
	}

	protected function save_pageset_params ()
	{
		if ( isset ( $this->psparm ) )
			$this->psparm->save_params();
	}

	protected function restore_pageset_params ()
	{
		if ( isset ( $this->psparm ) )
			$this->psparm->restore_params();
	}


	protected function clear_querymodules ()
	{
		$this->substree = array (
			'generator' => array(),
			'prop' => array(),
			'list' => array(),
			'meta' => array(),
		);
	}


	# ----- Converting params ----- #

	protected function convert_paramvalue ( $name, $value )
	{
		switch ( $name )
		{
			case 'namespace' :
				return $this->info->given_namespaces_ids ( $value );

			default :
				return $value;
		}
	}

	protected function convert_paramvalues ( $params_array )
	{
		foreach ( $params_array as $name => $value )
			$params_array[$name] = $this->convert_paramvalue ( $name, $value );
		return $params_array;
	}


	# ----- Overriding ----- #

	public function save_params ()
	{
		parent::save_params();
		$this->save_generator_params();
		$this->save_substree_nogen_params();
		$this->save_pageset_params();
	}

	public function restore_params ()
	{
		parent::restore_params();
		$this->restore_generator_params();
		$this->restore_substree_nogen_params();
		$this->restore_pageset_params();
	}


	public function params ()
	{
		if ( isset ( $this->psparm ) && count ( $this->substree['generator'] ) &&
			$this->info->is_available_list ( key ( $this->substree['generator'] ) ) )
		{
			$this->log ( "Both a list-type generator and a pageset " .
				"are set - will ignore the latter", LL_WARNING );
			unset ( $this->psparm );
		}

		foreach ( $this->substree as $groupname => $array )
		{
			$groupmodules = array();
			foreach ( $array as $modulename => $paramobject )
			{
				$moduleparams = $paramobject->params();
				$this->set_param ( $groupname, $modulename );
				$this->extra_params =
					array_merge ( $this->extra_params, $moduleparams );
			}
		}

		if ( isset ( $this->psparm ) )
			$this->extra_params =
				array_merge ( $this->extra_params, $this->psparm->params() );

		return parent::params();
	}


	# ----- Continues handling ----- #

	public function set_continue_params ()
	{
		$continues = $this->query_continue();
		$this->previous_query_continue = $continues; // MW or WM bug workaround - see parent::is_exhausted()

		# new continue processing
		if ( isset ( $continues['continue'] ) )
		{
			$this->extra_params = $continues;
			return count ( $continues );
		}

		# legacy continue processing
		$counter = 0;
		foreach ( $this->substree as $groupname => $array )
			if ( $groupname != 'generator' )
				foreach ( $array as $modulename => $paramobject )
				{
					if ( $paramobject->set_continue_params ( $continues ) )
						$counter++;
					else
						$paramobject->clear_params();
				}

		if ( ( $counter == 0 ) && count ( $this->substree['generator'] ) )
		{
			$paramobject = reset ( $this->substree['generator'] );
			if ( is_object ( $paramobject ) )
				if ( $paramobject->set_continue_params ( $continues ) )
				{
					$counter++;
					$paramobject->save_params();
					$this->restore_substree_nogen_params();
				}
		}

		return $counter;
	}


	# ----- Generator params handling ----- #


	public function nohooks__generator_object ( $hook_object, $name )
	{
		if ( ( ! $this->settings['lax_mode'] ) &&
			! in_array ( $name, $this->param_type ( 'generator' ) ) )
			throw new ApibotException_InternalError (
				"Query: No info about generator module '" . $name .
				"' - can't create its paramobject" );

		switch ( $name )
		{
			case "allcategories" :
				return new API_Params_List_Allcategories (
					$this->hooks, $this->info, $this->settings, true );
			case "allimages" :
				return new API_Params_List_Allimages (
					$this->hooks, $this->info, $this->settings, true );
			case "alllinks" :
				return new API_Params_List_Alllinks (
					$this->hooks, $this->info, $this->settings, true );
			case "allpages" :
				return new API_Params_List_Allpages (
					$this->hooks, $this->info, $this->settings, true );
			case "alltransclusions" :
				return new API_Params_List_Alltransclusions (
					$this->hooks, $this->info, $this->settings, true );
			case "backlinks" :
				return new API_Params_List_Backlinks (
					$this->hooks, $this->info, $this->settings, true );
			case "categorymembers" :
				return new API_Params_List_Categorymembers (
					$this->hooks, $this->info, $this->settings, true );
			case "embeddedin" :
				return new API_Params_List_EmbeddedIn (
					$this->hooks, $this->info, $this->settings, true );
			case "exturlusage" :
				return new API_Params_List_Exturlusage (
					$this->hooks, $this->info, $this->settings, true );
			case "imageusage" :
				return new API_Params_List_Imageusage (
					$this->hooks, $this->info, $this->settings, true );
			case "iwbacklinks" :
				return new API_Params_List_Iwbacklinks (
					$this->hooks, $this->info, $this->settings, true );
			case "langbacklinks" :
				return new API_Params_List_Langbacklinks (
					$this->hooks, $this->info, $this->settings, true );
			case "protectedtitles" :
				return new API_Params_List_Protectedtitles (
					$this->hooks, $this->info, $this->settings, true );
			case "querypage" :
				return new API_Params_List_Querypage (
					$this->hooks, $this->info, $this->settings, true );
			case "random" :
				return new API_Params_List_Random (
					$this->hooks, $this->info, $this->settings, true );
			case "recentchanges" :
				return new API_Params_List_Recentchanges (
					$this->hooks, $this->info, $this->settings, true );
			case "search" :
				return new API_Params_List_Search (
					$this->hooks, $this->info, $this->settings, true );
			case "watchlist" :
				return new API_Params_List_Watchlist (
					$this->hooks, $this->info, $this->settings, true );
			case "watchlistraw" :
				return new API_Params_List_Watchlistraw (
					$this->hooks, $this->info, $this->settings, true );
			case "categories" :
				return new API_Params_Property_Categories (
					$this->hooks, $this->info, $this->settings, true );
			case "duplicatefiles" :
				return new API_Params_Property_Duplicatefiles (
					$this->hooks, $this->info, $this->settings, true );
			case "images" :
				return new API_Params_Property_Images (
					$this->hooks, $this->info, $this->settings, true );
			case "links" :
				return new API_Params_Property_Links (
					$this->hooks, $this->info, $this->settings, true );
			case "templates" :
				return new API_Params_Property_Templates (
					$this->hooks, $this->info, $this->settings, true );
			default :
				if ( is_object ( $this->info ) )
				{
					if ( in_array ( $name, $this->param_type ( 'list' ) ) )
					{
						return new API_Params_List_ByName (
							$this->hooks, $this->info, $this->settings, true, $name );
					}
					elseif ( in_array ( $name, $this->param_type ( 'prop' ) ) )
					{
						return new API_Params_Property_ByName (
							$this->hooks, $this->info, $this->settings, true, $name );
					}
					else
					{
						throw new ApibotException_InternalError (
							"Query: Generator '" . $name .
							"' is of neither list nor page property type - " .
							"don't know how to create its paramobject" );
					}
				}
				else
				{
					throw new ApibotException_InternalError (
						"Query: Generator '" . $name .
						"' is not hardcoded, and I see no paraminfo for it - " .
						"don't know how to create its paramobject" );
				}
		}
	}


	protected function generator_object ( $name )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::generator_object',
			array ( $this, 'nohooks__generator_object' ),
			$this,
			$name
		);
	}


	public function set_generator ( $name )
	{
		if ( empty ( $this->substree['generator'] ) )
		{
			$this->substree['generator'] =
				array ( $name => $this->generator_object ( $name ) );
			return true;
		}
		else
		{
			return false;
		}
	}

	public function is_generator_paramname_ok ( $name )
	{
		if ( isset ( $this->substree['generator'] ) &&
			! empty ( $this->substree['generator'] ) )
		{
			$generator = reset ( $this->substree['generator'] );
			return $generator->is_paramname_ok ( $name );
		}
		else
		{
			return NULL;
		}
	}

	public function is_generator_param_under_limit ( $name )
	{
		if ( isset ( $this->substree['generator'] ) &&
			! empty ( $this->substree['generator'] ) )
		{
			$generator = reset ( $this->substree['generator'] );
			return $generator->is_param_under_limit ( $name );
		}
		else
		{
			return NULL;
		}
	}

	public function is_generator_paramvalue_ok ( $name, $value, $setmode = NULL )
	{
		if ( isset ( $this->substree['generator'] ) &&
			! empty ( $this->substree['generator'] ) )
		{
			$generator = reset ( $this->substree['generator'] );
			return $generator->is_paramvalue_ok ( $name, $value, $setmode );
		}
		else
		{
			return NULL;
		}
	}

	public function generator_param_isset ( $name )
	{
		if ( isset ( $this->substree['generator'] ) &&
			! empty ( $this->substree['generator'] ) )
		{
			$generator = reset ( $this->substree['generator'] );
			return $generator->param_isset ( $name );
		}
		else
		{
			return NULL;
		}
	}

	public function get_generator_param ( $name )
	{
		if ( isset ( $this->substree['generator'] ) &&
			! empty ( $this->substree['generator'] ) )
		{
			$paramdesc = reset ( $this->substree['generator'] );
			if ( $paramdesc )
				return $paramdesc->get_param ( $name );
		}
		return NULL;
	}

	public function get_generator_params ()
	{
		if ( isset ( $this->substree['generator'] ) &&
			! empty ( $this->substree['generator'] ) )
		{
			$paramdesc = reset ( $this->substree['generator'] );
			if ( $paramdesc )
				return $paramdesc->get_params();
		}
		return NULL;
	}

	public function set_generator_param ( $name, $value )
	{
		if ( isset ( $this->substree['generator'] ) &&
			! empty ( $this->substree['generator'] ) )
		{
			if ( $this->convert_paramvalues )
				$value = $this->convert_paramvalue ( $name, $value );

			$paramdesc = reset ( $this->substree['generator'] );
			if ( $paramdesc )
				return $paramdesc->set_param ( $name, $value );
		}
		return NULL;
	}

	public function set_generator_params ( $params_array )
	{
		if ( isset ( $this->substree['generator'] ) &&
			! empty ( $this->substree['generator'] ) )
		{
			if ( $this->convert_paramvalues )
				$params_array = $this->convert_paramvalues ( $params_array );

			$paramdesc = reset ( $this->substree['generator'] );
			if ( $paramdesc )
				return $paramdesc->set_params ( $params_array );
		}
		return NULL;
	}

	public function clear_generator_param ( $name, $value = NULL )
	{
		if ( isset ( $this->substree['generator'] ) &&
			! empty ( $this->substree['generator'] ) )
		{
			$paramdesc = reset ( $this->substree['generator'] );
			if ( $paramdesc )
				return $paramdesc->clear_param ( $name, $value );
		}
		return NULL;
	}

	public function clear_generator_params ()
	{
		if ( isset ( $this->substree['generator'] ) &&
			! empty ( $this->substree['generator'] ) )
		{
			$paramdesc = reset ( $this->substree['generator'] );
			if ( $paramdesc )
				return $paramdesc->clear_params();
		}
		return NULL;
	}


	# ----- Properties params handling ----- #


	public function nohooks__property_object ( $hook_object, $name )
	{
		if ( ! ( $this->settings['lax_mode'] || $this->is_property_ok ( $name ) ) )
			throw new ApibotException_InternalError (
				"Query: No info about page property '" . $name .
				"' - can't create its paramobject" );

		switch ( $name )
		{
			case "categories" :
				return new API_Params_Property_Categories (
					$this->hooks, $this->info, $this->settings, false );
			case "categoryinfo" :
				return new API_Params_Property_Categoryinfo (
					$this->hooks, $this->info, $this->settings, false );
			case "contributors" :
				return new API_Params_Property_Contributors (
					$this->hooks, $this->info, $this->settings, false );
			case "duplicatefiles" :
				return new API_Params_Property_Duplicatefiles (
					$this->hooks, $this->info, $this->settings, false );
			case "extlinks" :
				return new API_Params_Property_Extlinks (
					$this->hooks, $this->info, $this->settings, false );
			case "imageinfo" :
				return new API_Params_Property_Imageinfo (
					$this->hooks, $this->info, $this->settings, false );
			case "images" :
				return new API_Params_Property_Images (
					$this->hooks, $this->info, $this->settings, false );
			case "info" :
				return new API_Params_Property_Info (
					$this->hooks, $this->info, $this->settings, false );
			case "langlinks" :
				return new API_Params_Property_Langlinks (
					$this->hooks, $this->info, $this->settings, false );
			case "links" :
				return new API_Params_Property_Links (
					$this->hooks, $this->info, $this->settings, false );
			case "pageprops" :
				return new API_Params_Property_Pageprops (
					$this->hooks, $this->info, $this->settings, false );
			case "revisions" :
				return new API_Params_Property_Revisions (
					$this->hooks, $this->info, $this->settings, false );
			case "templates" :
				return new API_Params_Property_Templates (
					$this->hooks, $this->info, $this->settings, false );
			default :
				if ( is_object ( $this->info ) )
					return new API_Params_Property_ByName (
						$this->hooks, $this->info, $this->settings, false, $name );
				else
					throw new ApibotException_InternalError (
						"Query: Page property '" . $name .
						"' is not hardcoded, and I see no paraminfo for it - " .
						"don't know how to create its paramobject" );
		}
	}


	protected function property_object ( $name )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::property_object',
			array ( $this, 'nohooks__property_object' ),
			$this,
			$name
		);
	}


	public function is_property_ok ( $property )
	{
		return in_array ( $property, $this->param_type ( 'prop' ) );
	}

	public function add_property ( $property )
	{
		if ( ! isset ( $this->substree['prop'][$property] ) )
			$this->substree['prop'][$property] =
				$this->property_object ( $property );
	}

	public function del_property ( $property )
	{
		if ( isset ( $this->substree['prop'][$property] ) )
			unset ( $this->substree['prop'][$property] );
	}

	public function is_property_paramname_ok ( $property, $name )
	{
		if ( isset ( $this->substree['prop'][$property] ) )
			$sub = $this->substree['prop'][$property];
		else
			$sub = $this->property_object ( $property );

		return $sub->is_paramname_ok ( $name );
	}

	public function is_property_param_under_limit ( $property, $name )
	{
		if ( isset ( $this->substree['prop'][$property] ) )
			$sub = $this->substree['prop'][$property];
		else
			$sub = $this->property_object ( $property );

		return $sub->is_param_under_limit ( $name );
	}

	public function is_property_paramvalue_ok ( $property, $name, $value,
		$setmode = NULL )
	{
		if ( isset ( $this->substree['prop'][$property] ) )
			$sub = $this->substree['prop'][$property];
		else
			$sub = $this->property_object ( $property );

		return $sub->is_paramvalue_ok ( $name, $value, $setmode );
	}

	public function property_param_isset ( $property, $name )
	{
		if ( isset ( $this->substree['prop'][$property] ) )
			return $this->substree['prop'][$property]->param_isset ( $name );
		else
			return NULL;
	}

	public function get_property_param ( $property, $name )
	{
		if ( isset ( $this->substree['prop'][$property] ) )
			return $this->substree['prop'][$property]->get_param ( $name );
		else
			return NULL;
	}

	public function get_property_params ( $property )
	{
		if ( isset ( $this->substree['prop'][$property] ) )
			return $this->substree['prop'][$property]->get_params();
		else
			return NULL;
	}

	public function set_property_param ( $property, $name, $value )
	{
		if ( $this->convert_paramvalues )
			$value = $this->convert_paramvalue ( $name, $value );

		if ( ! isset ( $this->substree['prop'][$property] ) )
			$this->add_property ( $property );
		return $this->substree['prop'][$property]->set_param ( $name, $value );
	}

	public function set_property_params ( $property, $params_array )
	{
		if ( $this->convert_paramvalues )
			$params_array = $this->convert_paramvalues ( $params_array );

		if ( ! isset ( $this->substree['prop'][$property] ) )
			$this->add_property ( $property );
		return $this->substree['prop'][$property]->set_params ( $params_array );
	}

	public function set_properties_params ( $properties_array )
	{
		if ( is_array ( $properties_array ) )
		{
			foreach ( $properties_array as $property => $params_array )
				if ( ! $this->set_property_params ( $property, $params_array ) )
					return false;
			return true;
		}
		else
		{
			return false;
		}
	}

	public function clear_property_param ( $property, $name, $value = NULL )
	{
		if ( isset ( $this->substree['prop'][$property] ) )
			return $this->substree['prop'][$property]->clear_param ( $name, $value );
		else
			return NULL;
	}

	public function clear_property_params ( $property )
	{
		if ( isset ( $this->substree['prop'][$property] ) )
			return $this->substree['prop'][$property]->clear_params();
		else
			return NULL;
	}

	public function clear_properties_params ()
	{
		if ( isset ( $this->substree['prop'] ) &&
			is_array ( $this->substree['prop'] ) )
		{
			foreach ( $this->substree['prop'] as $property )
				$property->clear_params();
			return true;
		}
		else
			return NULL;
	}


	# ----- Lists params handling ----- #

	public function nohooks__list_object ( $hook_object, $name )
	{
		if ( ( ! $this->settings['lax_mode'] ) &&
		     ! in_array ( $name, $this->param_type ( 'list' ) ) )
			throw new ApibotException_InternalError (
				"Query: No info about list '" . $name .
				"' - can't create its paramobject" );

		switch ( $name )
		{
			case "allcategories" :
				return new API_Params_List_Allcategories (
					$this->hooks, $this->info, $this->settings, false );
			case "allimages" :
				return new API_Params_List_Allimages (
					$this->hooks, $this->info, $this->settings, false );
			case "alllinks" :
				return new API_Params_List_Alllinks (
					$this->hooks, $this->info, $this->settings, false );
			case "allpages" :
				return new API_Params_List_Allpages (
					$this->hooks, $this->info, $this->settings, false );
			case "alltransclusions" :
				return new API_Params_List_Alltransclusions (
					$this->hooks, $this->info, $this->settings, true );
			case "allusers" :
				return new API_Params_List_Allusers (
					$this->hooks, $this->info, $this->settings, false );
			case "backlinks" :
				return new API_Params_List_Backlinks (
					$this->hooks, $this->info, $this->settings, false );
			case "blocks" :
				return new API_Params_List_Blocks (
					$this->hooks, $this->info, $this->settings, false );
			case "categorymembers" :
				return new API_Params_List_Categorymembers (
					$this->hooks, $this->info, $this->settings, false );
			case "deletedrevs" :
				return new API_Params_List_Deletedrevs (
					$this->hooks, $this->info, $this->settings, false );
			case "embeddedin" :
				return new API_Params_List_Embeddedin (
					$this->hooks, $this->info, $this->settings, false );
			case "exturlusage" :
				return new API_Params_List_Exturlusage (
					$this->hooks, $this->info, $this->settings, false );
			case "filearchive" :
				return new API_Params_List_Filearchive (
					$this->hooks, $this->info, $this->settings, false );
			case "imageusage" :
				return new API_Params_List_Imageusage (
					$this->hooks, $this->info, $this->settings, false );
			case "iwbacklinks" :
				return new API_Params_List_Iwbacklinks (
					$this->hooks, $this->info, $this->settings, false );
			case "langbacklinks" :
				return new API_Params_List_Langbacklinks (
					$this->hooks, $this->info, $this->settings, false );
			case "logevents" :
				return new API_Params_List_Logevents (
					$this->hooks, $this->info, $this->settings, false );
			case "protectedtitles" :
				return new API_Params_List_Protectedtitles (
					$this->hooks, $this->info, $this->settings, false );
			case "querypage" :
				return new API_Params_List_Querypage (
					$this->hooks, $this->info, $this->settings, false );
			case "random" :
				return new API_Params_List_Random (
					$this->hooks, $this->info, $this->settings, false );
			case "recentchanges" :
				return new API_Params_List_Recentchanges (
					$this->hooks, $this->info, $this->settings, false );
			case "search" :
				return new API_Params_List_Search (
					$this->hooks, $this->info, $this->settings, false );
			case "tags" :
				return new API_Params_List_Tags (
					$this->hooks, $this->info, $this->settings, false );
			case "usercontribs" :
				return new API_Params_List_Usercontribs (
					$this->hooks, $this->info, $this->settings, false );
			case "users" :
				return new API_Params_List_Users (
					$this->hooks, $this->info, $this->settings, false );
			case "watchlist" :
				return new API_Params_List_Watchlist (
					$this->hooks, $this->info, $this->settings, false );
			case "watchlistraw" :
				return new API_Params_List_Watchlistraw (
					$this->hooks, $this->info, $this->settings, false );
			default :
				if ( is_object ( $this->info ) )
					return new API_Params_List_ByName (
						$this->hooks, $this->info, $this->settings, false, $name );
				else
					throw new ApibotException_InternalError (
						"Query: List '" . $name .
						"' is not hardcoded, and I see no paraminfo for it - " .
						"don't know how to create its paramobject" );
		}
	}


	protected function list_object ( $name )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::list_object',
			array ( $this, 'nohooks__list_object' ),
			$this,
			$name
		);
	}


	public function add_list ( $name )
	{
		$this->substree['list'][$name] = $this->list_object ( $name );
	}

	public function del_list ( $name )
	{
		unset ( $this->substree['list'][$name] );
	}

	public function is_list_paramname_ok ( $list, $name )
	{
		if ( isset ( $this->substree['list'][$list] ) )
			$sub = $this->substree['list'][$list];
		else
			$sub = $this->list_object ( $list );

		return $sub->is_paramname_ok ( $name );
	}

	public function is_list_param_under_limit ( $list, $name )
	{
		if ( isset ( $this->substree['list'][$list] ) )
			$sub = $this->substree['list'][$list];
		else
			$sub = $this->list_object ( $list );

		return $sub->is_param_under_limit ( $name );
	}

	public function is_list_paramvalue_ok ( $list, $name, $value,
		$setmode = NULL )
	{
		if ( isset ( $this->substree['list'][$list] ) )
			$sub = $this->substree['list'][$list];
		else
			$sub = $this->list_object ( $list );

		return $sub->is_paramvalue_ok ( $name, $value, $setmode );
	}

	public function list_param_isset ( $list, $name )
	{
		if ( isset ( $this->substree['list'][$list] ) )
			return $this->substree['list'][$list]->param_isset ( $name );
		else
			return NULL;
	}

	public function get_list_param ( $list, $name )
	{
		if ( isset ( $this->substree['list'][$list] ) )
			return $this->substree['list'][$list]->get_param ( $name );
		else
			return NULL;
	}

	public function get_list_params ( $list )
	{
		if ( isset ( $this->substree['list'][$list] ) )
			return $this->substree['list'][$list]->get_params();
		else
			return NULL;
	}

	public function set_list_param ( $list, $name, $value )
	{
		if ( $this->convert_paramvalues )
			$value = $this->convert_paramvalue ( $name, $value );

		if ( ! isset ( $this->substree['list'][$list] ) )
			$this->add_list ( $list );
		return $this->substree['list'][$list]->set_param ( $name, $value );
	}

	public function set_list_params ( $list, $params_array )
	{
		if ( $this->convert_paramvalues )
			$params_array = $this->convert_paramvalues ( $params_array );

		if ( ! isset ( $this->substree['list'][$list] ) )
			$this->add_list ( $list );
		return $this->substree['list'][$list]->set_params ( $params_array );
	}

	public function set_lists_params ( $lists_array )
	{
		if ( is_array ( $lists_array ) )
		{
			foreach ( $lists_array as $list => $params_array )
				if ( ! $this->set_list_params ( $list, $params_array ) )
					return false;
			return true;
		}
		else
		{
			return false;
		}
	}

	public function clear_list_param ( $list, $name, $value = NULL )
	{
		if ( isset ( $this->substree['list'][$list] ) )
			return $this->substree['list'][$list]->clear_param ( $name, $value );
		else
			return NULL;
	}

	public function clear_list_params ( $list )
	{
		if ( isset ( $this->substree['list'][$list] ) )
			return $this->substree['list'][$list]->clear_params();
		else
			return NULL;
	}

	public function clear_lists_params ()
	{
		if ( isset ( $this->substree['list'] ) &&
			is_array ( $this->substree['list'] ) )
		{
			foreach ( $this->substree['list'] as $list )
				$list->clear_params();
			return true;
		}
		else
			return NULL;
	}


	# ----- Meta params handling ----- #


	public function nohooks__meta_object ( $hook_object, $name )
	{
		if ( ( ! $this->settings['lax_mode'] ) &&
			! in_array ( $name, $this->param_type ( 'meta' ) ) )
			throw new ApibotException_InternalError (
				"Query: No info about meta '" . $name .
				"' - can't create its paramobject" );

		switch ( $name )
		{
			case "siteinfo" :
				return new API_Params_Meta_Siteinfo (
					$this->hooks, $this->info, $this->settings, false );
			case "userinfo" :
				return new API_Params_Meta_Userinfo (
					$this->hooks, $this->info, $this->settings, false );
			case "allmessages" :
				return new API_Params_Meta_Allmessages (
					$this->hooks, $this->info, $this->settings, false );
			case "filerepoinfo" :
				return new API_Params_Meta_Filerepoinfo (
					$this->hooks, $this->info, $this->settings, false );
			case "globaluserinfo" :
				return new API_Params_Meta_Globaluserinfo (
					$this->hooks, $this->info, $this->settings, false );
			case "tokens" :
				return new API_Params_Meta_Tokens (
					$this->hooks, $this->info, $this->settings, false );
			default :
				if ( is_object ( $this->info ) )
					return new API_Params_Meta_ByName (
						$this->hooks, $this->info, $this->settings, false, $name );
				else
					throw new ApibotException_InternalError (
						"Query: Meta '" . $name .
						"' is not hardcoded, and I see no paraminfo for it - " .
						"don't know how to create its paramobject" );
		}
	}


	protected function meta_object ( $name )
	{
		return $this->hooks->call (
			get_class ( $this ) . '::meta_object',
			array ( $this, 'nohooks__meta_object' ),
			$this,
			$name
		);
	}


	public function add_meta ( $name )
	{
		$this->substree['meta'][$name] = $this->meta_object ( $name );
	}

	public function del_meta ( $name )
	{
		unset ( $this->substree['meta'][$name] );
	}

	public function is_meta_paramname_ok ( $meta, $name )
	{
		if ( isset ( $this->substree['meta'][$meta] ) )
			$sub = $this->substree['meta'][$meta];
		else
			$sub = $this->meta_object ( $meta );

		return $sub->is_paramname_ok ( $name );
	}

	public function is_meta_param_under_limit ( $meta, $name )
	{
		if ( isset ( $this->substree['meta'][$meta] ) )
			$sub = $this->substree['meta'][$meta];
		else
			$sub = $this->meta_object ( $meta );

		return $sub->is_param_under_limit ( $name );
	}

	public function is_meta_paramvalue_ok ( $meta, $name, $value,
		$setmode = NULL )
	{
		if ( isset ( $this->substree['meta'][$meta] ) )
			$sub = $this->substree['meta'][$meta];
		else
			$sub = $this->meta_object ( $meta );

		return $sub->is_paramvalue_ok ( $name, $value, $setmode );
	}

	public function meta_param_isset ( $meta, $name )
	{
		if ( isset ( $this->substree['meta'][$meta] ) )
			return $this->substree['meta'][$meta]->param_isset ( $name );
		else
			return NULL;
	}

	public function get_meta_param ( $meta, $name )
	{
		if ( isset ( $this->substree['meta'][$meta] ) )
			return $this->substree['meta'][$meta]->get_param ( $name );
		else
			return NULL;
	}

	public function get_meta_params ( $meta )
	{
		if ( isset ( $this->substree['meta'][$meta] ) )
			return $this->substree['meta'][$meta]->get_params();
		else
			return NULL;
	}

	public function set_meta_param ( $meta, $name, $value )
	{
		if ( $this->convert_paramvalues )
			$value = $this->convert_paramvalue ( $name, $value );

		if ( ! isset ( $this->substree['meta'][$meta] ) )
			$this->add_meta ( $meta );
		return $this->substree['meta'][$meta]->set_param ( $name, $value );
	}

	public function set_meta_params ( $meta, $params_array )
	{
		if ( $this->convert_paramvalues )
			$params_array = $this->convert_paramvalues ( $params_array );

		if ( ! isset ( $this->substree['meta'][$meta] ) )
			$this->add_meta ( $meta );
		return $this->substree['meta'][$meta]->set_params ( $params_array );
	}

	public function set_metas_params ( $metas_array )
	{
		if ( is_array ( $metas_array ) )
		{
			foreach ( $metas_array as $meta => $params_array )
				if ( ! $this->set_meta_params ( $meta, $params_array ) )
					return false;
			return true;
		}
		else
		{
			return false;
		}
	}

	public function clear_meta_param ( $meta, $name, $value = NULL )
	{
		if ( isset ( $this->substree['meta'][$meta] ) )
			return $this->substree['meta'][$meta]->clear_param ( $name, $value );
		else
			return NULL;
	}

	public function clear_meta_params ( $meta )
	{
		if ( isset ( $this->substree['meta'][$meta] ) )
			return $this->substree['meta'][$meta]->clear_params();
		else
			return NULL;
	}

	public function clear_metas_params ()
	{
		if ( isset ( $this->substree['meta'] ) &&
			is_array ( $this->substree['meta'] ) )
		{
			foreach ( $this->substree['meta'] as $meta )
				$meta->clear_params();
			return true;
		}
		else
			return NULL;
	}


	# ----- Pageset module params handling ----- #


	public function nohooks__pageset_object ( $hook_object )
	{
		return new API_Params_Pageset ( $this->hooks, $this->info, $this->settings );
	}


	protected function pageset_object ()
	{
		return $this->hooks->call (
			get_class ( $this ) . '::pageset_object',
			array ( $this, 'nohooks__pageset_object' ),
			$this
		);
	}

	public function is_pageset_paramname_ok ( $name )
	{
		if ( isset ( $this->psparm ) )
			$sub = $this->psparm;
		else
			$sub = $this->pageset_object();

		return $sub->is_paramname_ok ( $name );
	}

	public function is_pageset_param_under_limit ( $name )
	{
		if ( isset ( $this->psparm ) )
			$sub = $this->psparm;
		else
			$sub = $this->pageset_object();

		return $sub->is_param_under_limit ( $name );
	}

	public function is_pageset_paramvalue_ok ( $name, $value, $setmode = NULL )
	{
		if ( isset ( $this->psparm ) )
			$sub = $this->psparm;
		else
			$sub = $this->pageset_object();

		return $sub->is_paramvalue_ok ( $name, $value, $setmode );
	}

	public function pageset_param_isset ( $name )
	{
		if ( isset ( $this->psparm ) )
			return $this->psparm->param_isset ( $name );
		else
			return NULL;
	}

	public function get_pageset_params ()
	{
		if ( isset ( $this->psparm ) )
			return $this->psparm->get_params();
		else
			return NULL;
	}

	public function set_pageset_param ( $name, $value )
	{
		if ( ! isset ( $this->psparm ) )
			$this->psparm = $this->pageset_object();
		return $this->psparm->set_param ( $name, $value );
	}

	public function set_pageset_params ( $params_array )
	{
		if ( ! isset ( $this->psparm ) )
			$this->psparm = new API_Params_Pageset (
				$this->hooks, $this->info, $this->settings );

		foreach ( $params_array as $name => $value )
			if ( ! $this->psparm->set_param ( $name, $value ) )
				return false;

		return true;
	}

	public function clear_pageset_param ( $name, $value = NULL )
	{
		if ( isset ( $this->psparm ) )
			return $this->psparm->clear_param ( $name, $value );
		else
			return NULL;
	}

	public function clear_pageset_params ()
	{
		if ( isset ( $this->psparm ) )
			return $this->psparm->clear_params();
		else
			return NULL;
	}

	public function set_titles ( $value )
	{
		return $this->set_pageset_param ( 'titles', $value );
	}

	public function set_pageids ( $value )
	{
		return $this->set_pageset_param ( 'pageids', $value );
	}

	public function set_revids ( $value )
	{
		return $this->set_pageset_param ( 'revids', $value );
	}

	public function titles_isset ()
	{
		return $this->pageset_param_isset ( 'titles' );
	}

	public function pageids_isset ()
	{
		return $this->pageset_param_isset ( 'pageids' );
	}

	public function revids_isset ()
	{
		return $this->pageset_param_isset ( 'revids' );
	}

	public function clear_titles ( $value = NULL )
	{
		return $this->clear_pageset_param ( 'titles', $value );
	}

	public function clear_pageids ( $value = NULL )
	{
		return $this->clear_pageset_param ( 'pageids', $value );
	}

	public function clear_revids ( $value = NULL )
	{
		return $this->clear_pageset_param ( 'revids', $value );
	}


	# ----- Params overriding ----- #


	public function get_param ( $paramname )
	{
		if ( is_array ( $paramname ) )
		{

			list ( $name, $value ) = each ( $paramname );

			if ( substr ( $name, 0, 1 ) == "_" )
			{
				$name = substr ( $name, 1 );
				if ( $name == "pageset" )
					return $this->get_pageset_param ( $value );
				else
					if ( isset ( $this->substree[$name] ) )
					{
						list ( $sub, $param ) = each ( $value );
						if ( isset ( $this->substree[$name][$sub] ) )
							return $this->substree[$name][$sub]->get_param ( $param );
					}
			}

			return NULL;

		}
		else
			return parent::get_param ( $paramname );
	}

	public function set_param ( $paramname, $paramvalue = "" )
	{
		if ( substr ( $paramname, 0, 1 ) == "_" )
		{
			$paramname = substr ( $paramname, 1 );
			if ( $paramname == "pageset" )
			{
				list ( $name, $value ) = each ( $paramvalue );
				return $this->set_pageset_param ( $name, $value );
			}
			elseif ( isset ( $this->substree[$paramname] ) )
				switch ( $paramname )
				{
					case "generator" :
						list ( $sub, $subparams ) = each ( $paramvalue );
						$this->set_generator ( $sub );
						return $this->set_generator_params ( $subparams );
					case "prop" :
						return $this->set_properties_params ( $paramvalue );
					case "list" :
						return $this->set_lists_params ( $paramvalue );
					case "meta" :
						return $this->set_metas_params ( $paramvalue );
				}

			return NULL;

		}
		else
			return parent::set_param ( $paramname, $paramvalue );
	}

	public function nohooks__get_params ( $hook_object )
	{
		$params = parent::nohooks__get_params ( $hook_object );

		if ( isset ( $this->psparm ) )
			$params['_pageset'] = $this->psparm->get_params();

		foreach ( $this->substree as $substype => $subsdata )
		{
			$substype = '_' . $substype;
			foreach ( $subsdata as $subkey => $subobject )
			{
				if ( ! isset ( $params[$substype] ) )
					$params[$substype] = array();
				$params[$substype][$subkey] = $subobject->get_params();
			}
		}

		return $params;
	}

	public function nohooks__set_params ( $hook_object, $params )
	{
		foreach ( $params as $paramname => $paramvalue )
			if ( ! $this->set_param ( $paramname, $paramvalue ) )
				return false;

		return true;
	}


}
