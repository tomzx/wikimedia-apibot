<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Bridge.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #



class Bridge
{

	public $core;
	public $info;


	# ----- Constructor ----- #

	function __construct ( $core )
	{
		$this->core = $core;
		$this->info = $core->info;  // shortcut to the Info functions
	}


	# ----- Tools ----- #

	public function log ( $message, $loglevel = LL_INFO, $logpreface = NULL )
	{
		return $this->core->log->log ( $message, $loglevel, $logpreface );
	}


	# ----- Queries ----- #

	# --- Pageset --- #

	public function query_titles ( $titles, $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/pageset/titles.php' );

		$query = new Query_Pageset_Titles (
			$this->core, $params, $settings );
		$query->titles = $titles;
		return $query;
	}

	public function query_pageids ( $pageids, $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/pageset/pageids.php' );

		$query = new Query_Pageset_Pageids (
			$this->core, $params, $settings );
		$query->pageids = $pageids;
		return $query;
	}

	public function query_revids ( $revids, $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/pageset/revids.php' );

		$query = new Query_Pageset_Revids (
			$this->core, $params, $settings );
		$query->revids = $revids;
		return $query;
	}


	# --- Page properties --- #

	public function query_property_categories ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/property/categories.php' );

		return new Query_Property_Categories (
			$this->core, $params, $settings );
	}

	public function query_titles_categories ( $titles, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_categories ( $params, $settings );
		$query->titles = $titles;
		return $query;
	}

	public function query_pageids_categories ( $pageids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_categories ( $params, $settings );
		$query->pageids = $pageids;
		return $query;
	}

	public function query_revids_categories ( $revids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_categories ( $params, $settings );
		$query->revids = $revids;
		return $query;
	}


	public function query_property_categoryinfo ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/property/categoryinfo.php' );

		return new Query_Property_Categoryinfo (
			$this->core, $params, $settings );
	}

	public function query_titles_categoryinfo ( $titles, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_categoryinfo ( $params, $settings );
		$query->titles = $titles;
		return $query;
	}

	public function query_pageids_categoryinfo ( $pageids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_categoryinfo ( $params, $settings );
		$query->pageids = $pageids;
		return $query;
	}

	public function query_revids_categoryinfo ( $revids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_categoryinfo ( $params, $settings );
		$query->revids = $revids;
		return $query;
	}


	public function query_property_contributors ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/property/contributors.php' );

		return new Query_Property_Contributors (
			$this->core, $params, $settings );
	}

	public function query_titles_contributors ( $titles, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_contributors ( $params, $settings );
		$query->titles = $titles;
		return $query;
	}

	public function query_pageids_contributors ( $pageids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_contributors ( $params, $settings );
		$query->pageids = $pageids;
		return $query;
	}

	public function query_revids_contributors ( $revids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_contributors ( $params, $settings );
		$query->revids = $revids;
		return $query;
	}


	public function query_property_duplicatefiles ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/property/duplicatefiles.php' );

		return new Query_Property_Duplicatefiles (
			$this->core, $params, $settings );
	}

	public function query_titles_duplicatefiles ( $titles, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_duplicatefiles ( $params, $settings );
		$query->titles = $titles;
		return $query;
	}

	public function query_pageids_duplicatefiles ( $pageids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_duplicatefiles ( $params, $settings );
		$query->pageids = $pageids;
		return $query;
	}

	public function query_revids_duplicatefiles ( $revids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_duplicatefiles ( $params, $settings );
		$query->revids = $revids;
		return $query;
	}


	public function query_property_extlinks ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/property/extlinks.php' );

		return new Query_Property_Extlinks (
			$this->core, $params, $settings );
	}

	public function query_titles_extlinks ( $titles, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_extlinks ( $params, $settings );
		$query->titles = $titles;
		return $query;
	}

	public function query_pageids_extlinks ( $pageids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_extlinks ( $params, $settings );
		$query->pageids = $pageids;
		return $query;
	}

	public function query_revids_extlinks ( $revids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_extlinks ( $params, $settings );
		$query->revids = $revids;
		return $query;
	}


	public function query_property_imageinfo ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/property/imageinfo.php' );

		return new Query_Property_Imageinfo (
			$this->core, $params, $settings );
	}

	public function query_titles_imageinfo ( $titles, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_imageinfo ( $params, $settings );
		$query->titles = $titles;
		return $query;
	}

	public function query_pageids_imageinfo ( $pageids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_imageinfo ( $params, $settings );
		$query->pageids = $pageids;
		return $query;
	}

	public function query_revids_imageinfo ( $revids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_imageinfo ( $params, $settings );
		$query->revids = $revids;
		return $query;
	}


	public function query_property_images ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/property/images.php' );

		return new Query_Property_Images (
			$this->core, $params, $settings );
	}

	public function query_titles_images ( $titles, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_images ( $params, $settings );
		$query->titles = $titles;
		return $query;
	}

	public function query_pageids_images ( $pageids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_images ( $params, $settings );
		$query->pageids = $pageids;
		return $query;
	}

	public function query_revids_images ( $revids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_images ( $params, $settings );
		$query->revids = $revids;
		return $query;
	}


	public function query_property_info ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/property/info.php' );

		return new Query_Property_Info (
			$this->core, $params, $settings );
	}

	public function query_titles_info ( $titles, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_info ( $params, $settings );
		$query->titles = $titles;
		return $query;
	}

	public function query_pageids_info ( $pageids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_info ( $params, $settings );
		$query->pageids = $pageids;
		return $query;
	}

	public function query_revids_info ( $revids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_info ( $params, $settings );
		$query->revids = $revids;
		return $query;
	}


	public function query_property_langlinks ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/property/langlinks.php' );

		return new Query_Property_Langlinks (
			$this->core, $params, $settings );
	}

	public function query_titles_langlinks ( $titles, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_langlinks ( $params, $settings );
		$query->titles = $titles;
		return $query;
	}

	public function query_pageids_langlinks ( $pageids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_langlinks ( $params, $settings );
		$query->pageids = $pageids;
		return $query;
	}

	public function query_revids_langlinks ( $revids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_langlinks ( $params, $settings );
		$query->revids = $revids;
		return $query;
	}


	public function query_property_links ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/property/links.php' );

		return new Query_Property_Links (
			$this->core, $params, $settings );
	}

	public function query_titles_links ( $titles, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_links ( $params, $settings );
		$query->titles = $titles;
		return $query;
	}

	public function query_pageids_links ( $pageids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_links ( $params, $settings );
		$query->pageids = $pageids;
		return $query;
	}

	public function query_revids_links ( $revids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_links ( $params, $settings );
		$query->revids = $revids;
		return $query;
	}


	public function query_property_pageprops ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/property/pageprops.php' );

		return new Query_Property_Pageprops (
			$this->core, $params, $settings );
	}

	public function query_titles_pageprops ( $titles, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_pageprops ( $params, $settings );
		$query->titles = $titles;
		return $query;
	}

	public function query_pageids_pageprops ( $pageids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_pageprops ( $params, $settings );
		$query->pageids = $pageids;
		return $query;
	}

	public function query_revids_pageprops ( $revids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_pageprops ( $params, $settings );
		$query->revids = $revids;
		return $query;
	}


	public function query_property_revisions ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/property/revisions.php' );

		return new Query_Property_Revisions (
			$this->core, $params, $settings );
	}

	public function query_titles_revisions ( $titles, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_revisions ( $params, $settings );
		$query->titles = $titles;
		return $query;
	}

	public function query_pageids_revisions ( $pageids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_revisions ( $params, $settings );
		$query->pageids = $pageids;
		return $query;
	}

	public function query_revids_revisions ( $revids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_revisions ( $params, $settings );
		$query->revids = $revids;
		return $query;
	}


	public function query_property_templates ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/property/templates.php' );

		return new Query_Property_Templates (
			$this->core, $params, $settings );
	}

	public function query_titles_templates ( $titles, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_templates ( $params, $settings );
		$query->titles = $titles;
		return $query;
	}

	public function query_pageids_templates ( $pageids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_templates ( $params, $settings );
		$query->pageids = $pageids;
		return $query;
	}

	public function query_revids_templates ( $revids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_templates ( $params, $settings );
		$query->revids = $revids;
		return $query;
	}


	public function query_property_by_name ( $modulename, $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/property/by_name.php' );

		return new Query_Property_ByName ( $modulename,
			$this->core, $params, $settings );
	}

	public function query_titles_by_name ( $modulename, $titles, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_by_name ( $modulename, $params, $settings );
		$query->titles = $titles;
		return $query;
	}

	public function query_pageids_by_name ( $modulename, $pageids, $params = NULL,
		$settings = array() )
	{
		$query = $this->query_property_by_name ( $modulename, $params, $settings );
		$query->pageids = $pageids;
		return $query;
	}

	public function query_revids_by_name ( $modulename, $revids, $params = NULL,
		$settings = array() )
	{

		$query = $this->query_property_by_name ( $modulename, $params, $settings );
		$query->revids = $revids;
		return $query;
	}


	# --- Generator --- #

	public function query_generator_allcategories ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/allcategories.php' );

		return new Query_Generator_Allcategories (
			$this->core, $params, $settings );
	}

	public function query_generator_allimages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/allimages.php' );

		return new Query_Generator_Allimages (
			$this->core, $params, $settings );
	}

	public function query_generator_alllinks ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/alllinks.php' );

		return new Query_Generator_Alllinks (
			$this->core, $params, $settings );
	}

	public function query_generator_allpages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/allpages.php' );

		return new Query_Generator_Allpages (
			$this->core, $params, $settings );
	}

	public function query_generator_alltransclusions ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/alltransclusions.php' );

		return new Query_Generator_Alltransclusions (
			$this->core, $params, $settings );
	}

	public function query_generator_backlinks ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/backlinks.php' );

		return new Query_Generator_Backlinks (
			$this->core, $params, $settings );
	}

	public function query_generator_categorymembers ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/categorymembers.php' );

		return new Query_Generator_Categorymembers (
			$this->core, $params, $settings );
	}

	public function query_generator_embeddedin ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/embeddedin.php' );

		return new Query_Generator_Embeddedin (
			$this->core, $params, $settings );
	}

	public function query_generator_exturlusage ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/exturlusage.php' );

		return new Query_Generator_Exturlusage (
			$this->core, $params, $settings );
	}

	public function query_generator_imageusage ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/imageusage.php' );

		return new Query_Generator_Imageusage (
			$this->core, $params, $settings );
	}

	public function query_generator_iwbacklinks ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/iwbacklinks.php' );

		return new Query_Generator_Iwbacklinks (
			$this->core, $params, $settings );
	}

	public function query_generator_langbacklinks ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/langbacklinks.php' );

		return new Query_Generator_Langbacklinks (
			$this->core, $params, $settings );
	}

	public function query_generator_protectedtitles ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/protectedtitles.php' );

		return new Query_Generator_Protectedtitles (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage ( $title, $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage.php' );

		return new Query_Generator_Querypage ( $title,
			$this->core, $params, $settings );
	}

	public function query_generator_random ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/random.php' );

		return new Query_Generator_Random (
			$this->core, $params, $settings );
	}

	public function query_generator_recentchanges ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/recentchanges.php' );

		return new Query_Generator_Recentchanges (
			$this->core, $params, $settings );
	}

	public function query_generator_search ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/search.php' );

		return new Query_Generator_Search (
			$this->core, $params, $settings );
	}

	public function query_generator_watchlist ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/watchlist.php' );

		return new Query_Generator_Watchlist (
			$this->core, $params, $settings );
	}

	public function query_generator_watchlistraw ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/watchlistraw.php' );

		return new Query_Generator_Watchlistraw (
			$this->core, $params, $settings );
	}

	public function query_generator_categories ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/categories.php' );

		return new Query_Generator_Categories (
			$this->core, $params, $settings );
	}

	public function query_generator_duplicatefiles ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/duplicatefiles.php' );

		return new Query_Generator_Duplicatefiles (
			$this->core, $params, $settings );
	}

	public function query_generator_images ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/images.php' );

		return new Query_Generator_Images (
			$this->core, $params, $settings );
	}

	public function query_generator_links ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/links.php' );

		return new Query_Generator_Links (
			$this->core, $params, $settings );
	}

	public function query_generator_templates ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/templates.php' );

		return new Query_Generator_Templates (
			$this->core, $params, $settings );
	}

	public function query_generator_by_name ( $modulename, $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/by_name.php' );

		return new Query_Generator_ByName ( $modulename,
			$this->core, $params, $settings );
	}


	public function query_generator_querypage_ancientpages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_ancientpages.php' );

		return new Query_Generator_Querypage_Ancientpages (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_brokenredirects ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_brokenredirects.php' );

		return new Query_Generator_Querypage_Brokenredirects (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_deadendpages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_deadendpages.php' );

		return new Query_Generator_Querypage_Deadendpages (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_disambiguations ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_disambiguations.php' );

		return new Query_Generator_Querypage_Disambiguations (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_doubleredirects ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_doubleredirects.php' );

		return new Query_Generator_Querypage_Doubleredirects (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_fewestrevisions ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_fewestrevisions.php' );
		return new Query_Generator_Querypage_Fewestrevisions (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_listredirects ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_listredirects.php' );

		return new Query_Generator_Querypage_Listredirects (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_lonelypages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_lonelypages.php' );

		return new Query_Generator_Querypage_Lonelypages (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_longpages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_longpages.php' );

		return new Query_Generator_Querypage_Longpages (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_mostcategories ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_mostcategories.php' );

		return new Query_Generator_Querypage_Mostcategories (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_mostimages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_mostimages.php' );

		return new Query_Generator_Querypage_Mostimages (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_mostlinked ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_mostlinkedcategories.php' );

		return new Query_Generator_Querypage_Mostlinked (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_mostlinkedcategories ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_mostlinked.php' );

		return new Query_Generator_Querypage_Mostlinkedcategories (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_mostlinkedtemplates ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_mostlinkedtemplates.php' );

		return new Query_Generator_Querypage_Mostlinkedtemplates (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_mostrevisions ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_mostrevisions.php' );

		return new Query_Generator_Querypage_Mostrevisions (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_shortpages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_shortpages.php' );

		return new Query_Generator_Querypage_Shortpages (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_uncategorizedcategories ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_uncategorizedcategories.php' );

		return new Query_Generator_Querypage_Uncategorizedcategories (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_uncategorizedimages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_uncategorizedimages.php' );

		return new Query_Generator_Querypage_Uncategorizedimages (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_uncategorizedpages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_uncategorizedpages.php' );

		return new Query_Generator_Querypage_Uncategorizedpages (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_uncategorizedtemplates ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_uncategorizedtemplates.php' );

		return new Query_Generator_Querypage_Uncategorizedtemplates (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_unusedcategories ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_unusedcategories.php' );

		return new Query_Generator_Querypage_Unusedcategories (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_unusedimages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_unusedimages.php' );

		return new Query_Generator_Querypage_Unusedimages (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_unusedtemplates ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_unusedtemplates.php' );

		return new Query_Generator_Querypage_Unusedtemplates (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_unwatchedpages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_unwatchedpages.php' );

		return new Query_Generator_Querypage_Unwatchedpages (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_wantedcategories ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_wantedcategories.php' );

		return new Query_Generator_Querypage_Wantedcategories (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_wantedfiles ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_wantedfiles.php' );

		return new Query_Generator_Querypage_Wantedfiles (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_wantedpages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_wantedpages.php' );

		return new Query_Generator_Querypage_Wantedpages (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_wantedtemplates ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_wantedtemplates.php' );

		return new Query_Generator_Querypage_Wantedtemplates (
			$this->core, $params, $settings );
	}

	public function query_generator_querypage_withoutinterwiki ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/generator/querypage_withoutinterwiki.php' );

		return new Query_Generator_Querypage_Withoutinterwiki (
			$this->core, $params, $settings );
	}


	# --- List --- #

	public function query_list_allcategories ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/allcategories.php' );

		return new Query_List_Allcategories (
			$this->core, $params, $settings );
	}

	public function query_list_allimages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/allimages.php' );

		return new Query_List_Allimages (
			$this->core, $params, $settings );
	}

	public function query_list_alllinks ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/alllinks.php' );

		return new Query_List_Alllinks (
			$this->core, $params, $settings );
	}

	public function query_list_allpages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/allpages.php' );

		return new Query_List_Allpages (
			$this->core, $params, $settings );
	}

	public function query_list_alltransclusions ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/alltransclusions.php' );

		return new Query_List_Alltransclusions (
			$this->core, $params, $settings );
	}

	public function query_list_allusers ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/allusers.php' );

		return new Query_List_Allusers (
			$this->core, $params, $settings );
	}

	public function query_list_backlinks ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/backlinks.php' );

		return new Query_List_Backlinks (
			$this->core, $params, $settings );
	}

	public function query_list_blocks ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/blocks.php' );

		return new Query_List_Blocks (
			$this->core, $params, $settings );
	}

	public function query_list_categorymembers ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/categorymembers.php' );

		return new Query_List_Categorymembers (
			$this->core, $params, $settings );
	}

	public function query_list_deletedrevs ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/deletedrevs.php' );

		return new Query_List_Deletedrevs (
			$this->core, $params, $settings );
	}

	public function query_list_embeddedin ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/embeddedin.php' );

		return new Query_List_Embeddedin (
			$this->core, $params, $settings );
	}

	public function query_list_exturlusage ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/exturlusage.php' );

		return new Query_List_Exturlusage (
			$this->core, $params, $settings );
	}

	public function query_list_filearchive ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/filearchive.php' );

		return new Query_List_Filearchive (
			$this->core, $params, $settings );
	}

	public function query_list_imageusage ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/imageusage.php' );

		return new Query_List_Imageusage (
			$this->core, $params, $settings );
	}

	public function query_list_iwbacklinks ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/iwbacklinks.php' );

		return new Query_List_Iwbacklinks (
			$this->core, $params, $settings );
	}

	public function query_list_langbacklinks ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/langbacklinks.php' );

		return new Query_List_Langbacklinks (
			$this->core, $params, $settings );
	}

	public function query_list_logevents ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/logevents.php' );

		return new Query_List_Logevents (
			$this->core, $params, $settings );
	}

	public function query_list_protectedtitles ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/protectedtitles.php' );

		return new Query_List_Protectedtitles (
			$this->core, $params, $settings );
	}

	public function query_list_querypage ( $title, $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage.php' );

		return new Query_List_Querypage (
			$title, $this->core, $params, $settings );
	}

	public function query_list_random ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/random.php' );

		return new Query_List_Random (
			$this->core, $params, $settings );
	}

	public function query_list_recentchanges ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/recentchanges.php' );

		return new Query_List_Recentchanges (
			$this->core, $params, $settings );
	}

	public function query_list_search ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/search.php' );

		return new Query_List_Search (
			$this->core, $params, $settings );
	}

	public function query_list_tags ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/tags.php' );

		return new Query_List_Tags (
			$this->core, $params, $settings );
	}

	public function query_list_usercontribs ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/usercontribs.php' );

		return new Query_List_Usercontribs (
			$this->core, $params, $settings );
	}

	public function query_list_users ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/users.php' );

		return new Query_List_Users (
			$this->core, $params, $settings );
	}

	public function query_list_watchlist ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/watchlist.php' );

		return new Query_List_Watchlist (
			$this->core, $params, $settings );
	}

	public function query_list_watchlistraw ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/watchlistraw.php' );

		return new Query_List_Watchlistraw (
			$this->core, $params, $settings );
	}

	public function query_list_by_name ( $modulename, $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/by_name.php' );

		return new Query_List_ByName ( $modulename,
			$this->core, $params, $settings );
	}


	public function query_list_querypage_ancientpages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_ancientpages.php' );

		return new Query_List_Querypage_Ancientpages (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_brokenredirects ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_brokenredirects.php' );

		return new Query_List_Querypage_Brokenredirects (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_deadendpages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_deadendpages.php' );

		return new Query_List_Querypage_Deadendpages (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_disambiguations ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_disambiguations.php' );

		return new Query_List_Querypage_Disambiguations (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_doubleredirects ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_doubleredirects.php' );

		return new Query_List_Querypage_Doubleredirects (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_fewestrevisions ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_fewestrevisions.php' );

		return new Query_List_Querypage_Fewestrevisions (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_listredirects ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_listredirects.php' );

		return new Query_List_Querypage_Listredirects (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_lonelypages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_lonelypages.php' );

		return new Query_List_Querypage_Lonelypages (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_longpages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_longpages.php' );

		return new Query_List_Querypage_Longpages (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_mostcategories ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_mostcategories.php' );

		return new Query_List_Querypage_Mostcategories (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_mostimages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_mostimages.php' );

		return new Query_List_Querypage_Mostimages (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_mostlinked ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_mostlinkedcategories.php' );

		return new Query_List_Querypage_Mostlinked (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_mostlinkedcategories ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_mostlinked.php' );

		return new Query_List_Querypage_Mostlinkedcategories (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_mostlinkedtemplates ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_mostlinkedtemplates.php' );

		return new Query_List_Querypage_Mostlinkedtemplates (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_mostrevisions ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_mostrevisions.php' );

		return new Query_List_Querypage_Mostrevisions (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_shortpages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_shortpages.php' );

		return new Query_List_Querypage_Shortpages (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_uncategorizedcategories ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_uncategorizedcategories.php' );

		return new Query_List_Querypage_Uncategorizedcategories (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_uncategorizedimages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_uncategorizedimages.php' );

		return new Query_List_Querypage_Uncategorizedimages (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_uncategorizedpages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_uncategorizedpages.php' );

		return new Query_List_Querypage_Uncategorizedpages (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_uncategorizedtemplates ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_uncategorizedtemplates.php' );

		return new Query_List_Querypage_Uncategorizedtemplates (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_unusedcategories ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_unusedcategories.php' );

		return new Query_List_Querypage_Unusedcategories (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_unusedimages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_unusedimages.php' );

		return new Query_List_Querypage_Unusedimages (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_unusedtemplates ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_unusedtemplates.php' );

		return new Query_List_Querypage_Unusedtemplates (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_unwatchedpages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_unwatchedpages.php' );

		return new Query_List_Querypage_Unwatchedpages (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_wantedcategories ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_wantedcategories.php' );

		return new Query_List_Querypage_Wantedcategories (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_wantedfiles ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_wantedfiles.php' );

		return new Query_List_Querypage_Wantedfiles (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_wantedpages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_wantedpages.php' );

		return new Query_List_Querypage_Wantedpages (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_wantedtemplates ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_wantedtemplates.php' );

		return new Query_List_Querypage_Wantedtemplates (
			$this->core, $params, $settings );
	}

	public function query_list_querypage_withoutinterwiki ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/list/querypage_withoutinterwiki.php' );

		return new Query_List_Querypage_Withoutinterwiki (
			$this->core, $params, $settings );
	}


	# --- Meta --- #

	public function query_meta_siteinfo ( $propname, $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/meta/siteinfo.php' );

		return new Query_Meta_Siteinfo ( $propname,
			$this->core, $params, $settings );
	}

	public function query_meta_siteinfo_dbrepllag ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/meta/siteinfo_dbrepllag.php' );

		return new Query_Meta_Siteinfo_Dbrepllag (
			$this->core, $params, $settings );
	}

	public function query_meta_siteinfo_extensions ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/meta/siteinfo_extensions.php' );

		return new Query_Meta_Siteinfo_Extensions (
			$this->core, $params, $settings );
	}

	public function query_meta_siteinfo_fileextensions ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/meta/siteinfo_fileextensions.php' );

		return new Query_Meta_Siteinfo_Fileextensions (
			$this->core, $params, $settings );
	}

	public function query_meta_siteinfo_general ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/meta/siteinfo_general.php' );

		return new Query_Meta_Siteinfo_General (
			$this->core, $params, $settings );
	}

	public function query_meta_siteinfo_interwikimap ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/meta/siteinfo_interwikimap.php' );

		return new Query_Meta_Siteinfo_Interwikimap (
			$this->core, $params, $settings );
	}

	public function query_meta_siteinfo_languages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/meta/siteinfo_languages.php' );

		return new Query_Meta_Siteinfo_Languages (
			$this->core, $params, $settings );
	}

	public function query_meta_siteinfo_magicwords ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/meta/siteinfo_magicwords.php' );

		return new Query_Meta_Siteinfo_Magicwords (
			$this->core, $params, $settings );
	}

	public function query_meta_siteinfo_namespacealiases ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/meta/siteinfo_namespacealiases.php' );

		return new Query_Meta_Siteinfo_Namespacealiases (
			$this->core, $params, $settings );
	}

	public function query_meta_siteinfo_namespaces ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/meta/siteinfo_namespaces.php' );

		return new Query_Meta_Siteinfo_Namespaces (
			$this->core, $params, $settings );
	}

	public function query_meta_siteinfo_rightsinfo ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/meta/siteinfo_rightsinfo.php' );

		return new Query_Meta_Siteinfo_Rightsinfo (
			$this->core, $params, $settings );
	}

	public function query_meta_siteinfo_specialpagealiases ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/meta/siteinfo_specialpagealiases.php' );

		return new Query_Meta_Siteinfo_Specialpagealiases (
			$this->core, $params, $settings );
	}

	public function query_meta_siteinfo_statistics ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/meta/siteinfo_statistics.php' );

		return new Query_Meta_Siteinfo_Statistics (
			$this->core, $params, $settings );
	}

	public function query_meta_siteinfo_usergroups ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/meta/siteinfo_usergroups.php' );

		return new Query_Meta_Siteinfo_Usergroups (
			$this->core, $params, $settings );
	}


	public function query_meta_userinfo ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/meta/userinfo.php' );

		return new Query_Meta_Userinfo (
			$this->core, $params, $settings );
	}


	public function query_meta_allmessages ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/meta/allmessages.php' );

		return new Query_Meta_Allmessages (
			$this->core, $params, $settings );
	}


	public function query_meta_filerepoinfo ( $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/meta/filerepoinfo.php' );

		return new Query_Meta_Filerepoinfo (
			$this->core, $params, $settings );
	}


	public function query_meta_by_name ( $modulename, $params = NULL,
		$settings = array() )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../core/queries/meta/by_name.php' );

		return new Query_Meta_ByName ( $modulename,
			$this->core, $params, $settings );
	}


	# ----- Tasks ----- #

	public function block ( $user, $reason, $expiry = NULL,
		$anononly = NULL, $nocreate = NULL, $autoblock = NULL, $noemail = NULL )
	{
		$params = array (
			'user' => $user,
			'reason' => $reason,
			'expiry' => $expiry,
			'anononly' => $anononly,
			'nocreate' => $nocreate,
			'autoblock' => $autoblock,
			'noemail' => $noemail,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/block.php' );

		$task = new Task_Block ( $this->core );
		return $task->go ( $params );
	}

	public function createaccount ( $name, $password, $domain = NULL,
		$email = NULL, $realname = NULL, $mailpassword = NULL,
		$reason = NULL, $language = NULL )
	{
		$params = array (
			'name' => $name,
			'password' => $password,
			'domain' => $domain,
			'email' => $email,
			'realname' => $realname,
			'mailpassword' => $mailpassword,
			'reason' => $reason,
			'language' => $language,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/createaccount.php' );

		$task = new Task_Createaccount ( $this->core );
		return $task->go ( $params );
	}

	public function delete_pageid ( $pageid, $reason, $watch = NULL,
		$oldimage = NULL )
	{
		$params = array (
			'pageid' => $pageid,
			'reason' => $reason,
			'watch' => $watch,
			'oldimage' => $oldimage,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/delete_pageid.php' );

		$task = new Task_DeletePageid ( $this->core );
		return $task->go ( $params );
	}

	public function delete_title ( $title, $reason, $watch = NULL,
		$oldimage = NULL )
	{
		$params = array (
			'title' => $title,
			'reason' => $reason,
			'watch' => $watch,
			'oldimage' => $oldimage,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/delete_title.php' );

		$task = new Task_DeleteTitle ( $this->core );
		return $task->go ( $params );
	}

	public function edit ( $page, $summary,
		$is_minor = NULL, $is_bot = NULL, $watch = NULL,
		$recreate = NULL, $createonly = NULL, $nocreate = NULL )
	{
		$params = array (
			'page' => $page,
			'summary' => $summary,
			'bot' => $is_bot,
			'minor' => $is_minor,
			'watch' => $watch,
			'recreate' => $recreate,
			'createonly' => $createonly,
			'nocreate' => $nocreate,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/edit.php' );

		$task = new Task_Edit ( $this->core );
		return $task->go ( $params );
	}

	public function emailuser ( $user, $subject, $text, $ccme = NULL )
	{
		$params = array (
			'user' => $user,
			'subject' => $subject,
			'text' => $text,
			'ccme' => $ccme,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/emailuser.php' );

		$task = new Task_Emailuser ( $this->core );
		return $task->go ( $params );
	}

	public function expandtemplates ( $text, $title = NULL )
	{
		$params = array (
			'text' => $text,
			'title' => $title,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/expandtemplates.php' );

		$task = new Task_Expandtemplates ( $this->core );
		return $task->go ( $params );
	}

	public function fetch_editable ( $title, $revid = NULL, $section = NULL,
		$log_fetch = NULL )
	{
		$params = array (
			'title' => $title,
			'revid' => $revid,
			'section' => $section,
			'log_result' => $log_fetch,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/fetch_editable.php' );

		$task = new Task_FetchEditable ( $this->core );
		return $task->go ( $params );
	}

	public function fetch_title ( $title, $properties = NULL, $revid = NULL,
		$section = NULL, $log_fetch = NULL )
	{
		$params = array (
			'title' => $title,
			'properties' => $properties,
			'revid' => $revid,
			'section' => $section,
			'log_result' => $log_fetch,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/fetch_title.php' );

		$task = new Task_FetchTitle ( $this->core );
		return $task->go ( $params );
	}

	public function fetch_pageid ( $pageid, $properties = NULL, $revid = NULL,
		$section = NULL, $log_fetch = NULL )
	{
		$params = array (
			'pageid' => $pageid,
			'properties' => $properties,
			'revid' => $revid,
			'section' => $section,
			'log_result' => $log_fetch,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/fetch_pageid.php' );

		$task = new Task_FetchPageid ( $this->core );
		return $task->go ( $params );
	}

	public function fetch_revid ( $revid, $properties = NULL,
		$section = NULL, $log_fetch = NULL )
	{
		$params = array (
			'revid' => $revid,
			'properties' => $properties,
			'revid' => $revid,
			'section' => $section,
			'log_result' => $log_fetch,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/fetch_revid.php' );

		$task = new Task_FetchRevid ( $this->core );
		return $task->go ( $params );
	}

	public function fetch_filename ( $filename, $properties = NULL,
		$log_fetch = NULL )
	{
		$params = array (
			'title' => $filename,
			'properties' => $properties,
			'log_result' => $log_fetch,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/fetch_filename.php' );

		$task = new Task_FetchFilename ( $this->core );
		return $task->go ( $params );
	}

	public function import_interwiki ( $title, $summary, $iwcode,
		$fullhistory = NULL, $into_namespace = NULL, $templates = NULL )
	{
		$params = array (
			'title' => $title,
			'summary' => $summary,
			'iwcode' => $iwcode,
			'fullhistory' => $fullhistory,
			'namespace' => $into_namespace,
			'templates' => $templates,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/import_interwiki.php' );

		$task = new Task_ImportInterwiki ( $this->core );
		return $task->go ( $params );
	}

	public function import_xml ( $xml_upload, $summary )
	{
		$params = array (
			'xml' => $xml_upload,
			'summary' => $summary,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/import_xml.php' );

		$task = new Task_ImportXML ( $this->core );
		return $task->go ( $params );
	}

	public function move_title ( $title, $to_title, $reason,
		$noredirect = NULL, $movetalk = NULL, $movesubpages = NULL, $watch = NULL )
	{
		$params = array (
			'title' => $title,
			'reason' => $reason,
			'to' => $to_title,
			'noredirect' => $noredirect,
			'movetalk' => $movetalk,
			'movesubpages' => $movesubpages,
			'watch' => $watch,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/move_title.php' );

		$task = new Task_MoveTitle ( $this->core );
		return $task->go ( $params );
	}

	public function move_pageid ( $pageid, $to_title, $reason,
		$noredirect = NULL, $movetalk = NULL, $movesubpages = NULL, $watch = NULL )
	{
		$params = array (
			'pageid' => $pageid,
			'reason' => $reason,
			'to' => $to_title,
			'noredirect' => $noredirect,
			'movetalk' => $movetalk,
			'movesubpages' => $movesubpages,
			'watch' => $watch,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/move_pageid.php' );

		$task = new Task_MovePageid ( $this->core );
		return $task->go ( $params );
	}

	public function parse_page ( $title, $prop = NULL, $pst = NULL,
		$uselang = NULL )
	{
		$params = array (
			'title' => $title,
			'prop' => $prop,
			'pst' => $pst,
			'uselang' => $uselang,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/parse_page.php' );

		$task = new Task_ParsePage ( $this->core );
		return $task->go ( $params );
	}

	public function parse_text ( $text, $title = NULL, $prop = NULL,
		$pst = NULL, $uselang = NULL )
	{
		$params = array (
			'text' => $text,
			'title' => $title,
			'prop' => $prop,
			'pst' => $pst,
			'uselang' => $uselang,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/parse_text.php' );

		$task = new Task_ParseText ( $this->core );
		return $task->go ( $params );
	}

	public function patrol ( $rcid )
	{
		$params = array (
			'rcid' => $rcid,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/patrol.php' );

		$task = new Task_Patrol ( $this->core );
		return $task->go ( $params );
	}

	public function protect ( $title, $reason, $protections,
		$expiry = NULL, $cascade = false )
	{
		$params = array (
			'title' => $title,
			'reason' => $reason,
			'protections' => $protections,
			'expiry' => $expiry,
			'cascade' => $cascade,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/protect.php' );

		$task = new Task_Protect ( $this->core );
		return $task->go ( $params );
	}

	public function purge ( $titles )
	{
		$params = array (
			'titles' => $titles,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/purge.php' );

		$task = new Task_Purge ( $this->core );
		return $task->go ( $params );
	}

	public function rollback ( $title, $summary, $user, $markbot = NULL )
	{
		$params = array (
			'title' => $title,
			'summary' => $summary,
			'user' => $user,
			'markbot' => $markbot,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/rollback.php' );

		$task = new Task_Rollback ( $this->core );
		return $task->go ( $params );
	}

	public function unblock ( $user, $reason, $block_id = NULL )
	{
		$params = array (
			'user' => $user,
			'reason' => $reason,
			'id' => $block_id,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/unblock.php' );

		$task = new Task_Unblock ( $this->core );
		return $task->go ( $params );
	}

	public function undelete ( $title, $reason, $timestamps = NULL )
	{
		$params = array (
			'title' => $title,
			'reason' => $reason,
			'timestamps' => $timestamps,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/undelete.php' );

		$task = new Task_Undelete ( $this->core );
		return $task->go ( $params );
	}

	public function undo ( $title, $summary, $undo_revid = NULL, $to_revid = NULL,
		$is_minor = NULL, $is_bot = NULL, $watch = NULL )
	{
		$params = array (
			'title' => $title,
			'summary' => $summary,
			'undo' => $undo_revid,
			'undoafter' => $to_revid,
			'bot' => $is_bot,
			'minor' => $is_minor,
			'watch' => $watch,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/undo.php' );

		$task = new Task_Undo ( $this->core );
		return $task->go ( $params );
	}

	public function unwatch ( $title )
	{
		$params = array (
			'title' => $title,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/unwatch.php' );

		$task = new Task_Unwatch ( $this->core );
		return $task->go ( $params );
	}

	public function upload_file ( $filename, $comment, $file_body,
		$text = NULL, $watch = NULL, $ignorewarnings = NULL )
	{
		$params = array (
			'title' => $filename,
			'comment' => $comment,
			'file' => $file_body,
			'text' => $text,
			'watch' => $watch,
			'ignorewarnings' => $ignorewarnings,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/upload_file.php' );

		$task = new Task_UploadFile ( $this->core );
		return $task->go ( $params );
	}

	public function upload_url ( $filename, $comment, $url,
		$text = NULL, $watch = NULL, $ignorewarnings = NULL, $asyncdownload = NULL )
	{
		$params = array (
			'title' => $filename,
			'comment' => $comment,
			'url' => $url,
			'text' => $text,
			'watch' => $watch,
			'ignorewarnings' => $ignorewarnings,
			'asyncdownload' => $async,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/upload_url.php' );

		$task = new Task_UploadURL ( $this->core );
		return $task->go ( $params );
	}

	public function userrights ( $user, $reason,
		$add_groups = NULL, $remove_groups = NULL )
	{
		$params = array (
			'user' => $user,
			'reason' => $reason,
			'add' => $add_groups,
			'remove' => $remove_groups,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/userrights.php' );

		$task = new Task_Userrights ( $this->core );
		return $task->go ( $params );
	}

	public function watch ( $title, $watch = true )
	{
		$params = array (
			'title' => $title,
			'unwatch' => ( ! $watch ),
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../core/tasks/watch.php' );

		$task = new Task_Watch ( $this->core );
		return $task->go ( $params );
	}


	# ----- Other ----- #

	public function xfer ( $uri, $vars = array(), $files = array(),
		$mustbeposted = false )
	{
		if ( $this->core->browser->xfer ( $uri, $vars, $files, $mustbeposted ) )
			return $this->core->browser->content;
		else
			return false;
	}


}



class Standalone_Bridge extends Bridge
{

	function __construct ( $account, $settings = array() )
	{
		$core = new core ( $account, $settings );
		parent::__construct ( $core );
	}

}
