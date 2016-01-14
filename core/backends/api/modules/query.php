<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Query.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class API_Module_Query extends API_Module
{

	protected $extra_params = array();  // from the querymodules


	protected $previous_query_continue = NULL;  // MW/WM workaround - see is_exhausted()


	# ----- Implemented ----- #

	public function modulename ()
	{
		return "query";
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 10800 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "",
			'params' => array (
				'generator' => array (
					'type' => array (
						"allpages",
					),
				),
				'list' => array (
					'type' => array (
						"allpages",
					),
					'multi' => true,
					'limit' => 50,
				),
				'prop' => array (
					'type' => array (
						"info",
						"revisions",
					),
					'multi' => true,
					'limit' => 50,
				),
				'meta' => array (
					'type' => array (
						"siteinfo",
					),
					'multi' => true,
					'limit' => 50,
				),
				'redirects' => array (
					'type' => "boolean",
					'default' => false,
				),
			),
		);

		if ( $mwverno >= 10900 )
		{
			$paramdesc['params']['generator']['type'][] = "backlinks";
			$paramdesc['params']['generator']['type'][] = "embeddedin";
			$paramdesc['params']['generator']['type'][] = "imageusage";
			$paramdesc['params']['generator']['type'][] = "watchlist";

			$paramdesc['params']['list']['type'][] = "backlinks";
			$paramdesc['params']['list']['type'][] = "embeddedin";
			$paramdesc['params']['list']['type'][] = "imageusage";
			$paramdesc['params']['list']['type'][] = "logevents";
			$paramdesc['params']['list']['type'][] = "recentchanges";
			$paramdesc['params']['list']['type'][] = "usercontribs";

		}

		if ( $mwverno >= 11100 )
		{
			$paramdesc['params']['indexpageids'] = array (
				'type' => "boolean",
				'default' => false,
			);

			$paramdesc['params']['generator']['type'][] = "alllinks";
			$paramdesc['params']['generator']['type'][] = "categories";
			$paramdesc['params']['generator']['type'][] = "categorymembers";
			$paramdesc['params']['generator']['type'][] = "exturlusage";
			$paramdesc['params']['generator']['type'][] = "images";
			$paramdesc['params']['generator']['type'][] = "links";
			$paramdesc['params']['generator']['type'][] = "templates";
			$paramdesc['params']['generator']['type'][] = "search";

			$paramdesc['params']['list']['type'][] = "alllinks";
			$paramdesc['params']['list']['type'][] = "allusers";
			$paramdesc['params']['list']['type'][] = "categorymembers";
			$paramdesc['params']['list']['type'][] = "exturlusage";
			$paramdesc['params']['list']['type'][] = "search";

			$paramdesc['params']['prop']['type'][] = "categories";
			$paramdesc['params']['prop']['type'][] = "extlinks";
			$paramdesc['params']['prop']['type'][] = "images";
			$paramdesc['params']['prop']['type'][] = "imageinfo";
			$paramdesc['params']['prop']['type'][] = "links";
			$paramdesc['params']['prop']['type'][] = "templates";

			$paramdesc['params']['meta']['type'][] = "userinfo";
		}

		if ( $mwverno >= 11200 )
		{
			$paramdesc['params']['generator']['type'][] = "allcategories";
			$paramdesc['params']['generator']['type'][] = "random";

			$paramdesc['params']['list']['type'][] = "allcategories";
			$paramdesc['params']['list']['type'][] = "blocks";
			$paramdesc['params']['list']['type'][] = "deletedrevs";
			$paramdesc['params']['list']['type'][] = "random";
			$paramdesc['params']['list']['type'][] = "users";

			$paramdesc['params']['meta']['type'][] = "allmessages";
		}

		if ( $mwverno >= 11300 )
		{
			$paramdesc['params']['generator']['type'][] = "allimages";

			$paramdesc['params']['list']['type'][] = "allimages";

			$paramdesc['params']['prop']['type'][] = "categoryinfo";
		}

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['generator']['type'][] = "duplicatefiles";
			$paramdesc['params']['generator']['type'][] = "watchlistraw";

			$paramdesc['params']['list']['type'][] = "watchlistraw";

			$paramdesc['params']['prop']['type'][] = "duplicatefiles";
		}

		if ( $mwverno >= 11500 )
		{
			$paramdesc['params']['export'] = array (
				'type' => "boolean",
				'default' => false,
			);
			$paramdesc['params']['exportnowrap'] = array (
				'type' => "boolean",
				'default' => false,
			);

			$paramdesc['params']['generator']['type'][] = "protectedtitles";

			$paramdesc['params']['list']['type'][] = "protectedtitles";
		}

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['list']['type'][] = "tags";
		}

		if ( $mwverno >= 11700 )
		{
			# NO guarantee the wiki language will support converttitles! Few do!
			$paramdesc['params']['converttitles'] = array (
				'type' => "boolean",
				'default' => false,
			);

			$paramdesc['params']['generator']['type'][] = "iwbacklinks";

			$paramdesc['params']['list']['type'][] = "filearchive";
			$paramdesc['params']['list']['type'][] = "iwbacklinks";

			$paramdesc['params']['prop']['type'][] = "iwlinks";
			$paramdesc['params']['prop']['type'][] = "stashimageinfo";
			$paramdesc['params']['prop']['type'][] = "pageprops";
		}

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['generator']['type'][] = "langbacklinks";
			$paramdesc['params']['generator']['type'][] = "querypage";

			$paramdesc['params']['generator']['type'][] = "recentchanges";

			$paramdesc['params']['list']['type'][] = "langbacklinks";
			$paramdesc['params']['list']['type'][] = "querypage";
		}

		if ( $mwverno >= 11900 )
		{
			$paramdesc['params']['iwurl'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		if ( $mwverno >= 12100 )
		{
			$paramdesc['params']['continue'] = array (
				'type' => "string",
				'default' => "",
			);
		}

		if ( $mwverno >= 12200 )
		{
			$paramdesc['params']['meta']['type'][] = "filerepoinfo";
		}

		return $paramdesc;
	}


	# ----- Overriding ----- #


	# ----- Tools ----- #


	public function is_paramvalue_ok ( $name, $value, $lax_mode = NULL )
	{
		if ( ( $name == "continue" ) && ( $value == "" ) )
			return true;
		return parent::is_paramvalue_ok ( $name, $value, $lax_mode );
	}


	public function clear_params ()
	{
		parent::clear_params();
		$this->extra_params = array();
	}

	public function params ()
	{
		return array_merge ( $this->extra_params, parent::params() );
	}


	# ----- Extra params management ----- #

	public function get_extra_param ( $name )
	{
		if ( isset ( $this->extra_params[$name] ) )
			return $this->extra_params[$name];
		else
			return NULL;
	}

	public function set_extra_param ( $name, $value )
	{
		$this->extra_params[$name] = $value;
	}

	public function get_extra_params ()
	{
		return $this->extra_params;
	}

	public function set_extra_params ( $params_array )
	{
		$this->extra_params = $params_array;
	}


	# ----- Data access ----- #

	# --- Misc --- #

	public function normalized ( $what = NULL )
	{
		$normalized = $this->results_element ( 'normalized' );
		if ( is_null ( $what ) )
		{
			return $normalized;
		}
		else
		{
			foreach ( $normalized as $normalize )
				if ( $normalize['from'] == $what )
					return $normalize['to'];
			return false;
		}
	}


	public function query_continue ( $what = NULL )
	{
		$result = $this->data_area ( 'continue' ); // the new continue format
		if ( ! is_null ( $result )
			&& ! is_null ( $what )
			&& isset ( $result[$what] )
		)
			$result = $result[$what];

		if ( empty ( $result ) )
			$result = $this->array_or_element (  // the legacy continue format
				$this->data_area ( 'query-continue' ), $what );

		return $result;
	}

	public function is_exhausted ()
	{
		# A MediaWiki or Wikimedia files database bug workaround -
		# without it should be just "return is_null ( $this->query_continue() );"

		$query_continue = $this->query_continue();

		return ( is_null ( $query_continue ) ||
			( print_r ( $query_continue, true ) === print_r ( $this->previous_query_continue, true ) ) );
	}


	# --- Area elements --- #
	# Typical area elements are "normalized", "query-continue", "redirects" etc.

	public function query_area ( $area_key )
	{
		$results = $this->results_area();
		return $this->element ( $results, $area_key );
	}

	public function query_area_elements_count ( $area_key )
	{
		return $this->elements_count ( $this->query_area ( $area_key ) );
	}

	public function query_area_elements_keys ( $area_key )
	{
		return $this->elements_keys ( $this->query_area ( $area_key ) );
	}

	public function query_area_element ( $area_key, $element_key )
	{
		return $this->data_element ( $area_key,
			$this->element ( $this->query_area ( $area_key ), $element_key ) );
	}

	public function query_area_first_element ( $area_key )
	{
		if ( isset ( $this->data['query'][$area_key] ) &&
			is_array ( $this->data['query'][$area_key] ) )
		{
			return reset ( $this->data['query'][$area_key] );
		}
		else
		{
			return NULL;
		}
	}

	public function query_area_next_element ( $area_key )
	{
		if ( isset ( $this->data['query'][$area_key] ) &&
			is_array ( $this->data['query'][$area_key] ) )
		{
			return next ( $this->data['query'][$area_key] );
		}
		else
		{
			return NULL;
		}
	}

	public function query_area_last_element ( $area_key )
	{
		if ( isset ( $this->data['query'][$area_key] ) &&
			is_array ( $this->data['query'][$area_key] ) )
		{
			return end ( $this->data['query'][$area_key] );
		}
		else
		{
			return NULL;
		}
	}


	# ----- Servicing params ----- #

	public function set_continue_params ()
	{
		$this->previous_query_continue = $this->query_continue(); // MW/WM bug workaround - see is_exhausted()
		return $this->set_params ( $this->query_continue() );
	}


	# ----- Xfer servicing ----- #

	public function nohooks__xfer ( $hook_object )
	{
		$this->save_params();
		$this->set_param ( 'continue', "" );
		return parent::nohooks__xfer ( $hook_object );
	}

	public function nohooks__next ( $hook_object )
	{
		if ( $this->is_exhausted() )
			return false;
		$this->restore_params();
		$this->set_continue_params();
		return parent::nohooks__xfer ( $hook_object );
	}


	public function next ()
	{
		return $this->hooks->call (
			get_class ( $this ) . '::next',
			array ( $this, 'nohooks__next' ),
			$this
		);
	}


}
