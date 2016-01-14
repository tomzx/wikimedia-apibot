<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Info: API backend
#
#  Info fetching.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../../_generic/info.php' );


class Info_API extends Info_Generic
{


	# ----- Info and index storages ----- #


	protected $info = array();
	protected $index = array();

	protected $old_info = array();


	# ----- Exchanger calls ----- #

	protected function exchange ( $action, $params )
	{
		$params['action'] = $action;
		$result = $this->xfer ( $params );
		if ( $result )
			$result = &$this->exchanger->data[$action];
		return $result;
	}


	protected function exchange_query ( $params )
	{
		return $this->exchange ( "query", $params );
	}


	protected function exchange_siteinfo ( $params )
	{
		$params['meta'] = "siteinfo";
		return $this->exchange_query ( $params );
	}

	protected function exchange_generalinfo ()
	{
		$result = $this->exchange_siteinfo ( array ( 'siprop' => "general" ) );
		if ( $result )
			$result = $result['general'];
		return $result;
	}


	protected function exchange_userinfo ( $params )
	{
		$params['meta'] = "userinfo";
		$result = $this->exchange_query ( $params );
		if ( $result )
			$result = $result['userinfo'];
		return $result;
	}


	protected function exchange_allmessages ( $params )
	{
		$params['meta'] = "allmessages";
		$result = $this->exchange_query ( $params );
		if ( $result )
			$result = $result['allmessages'];
		return $result;
	}


	protected function exchange_filerepoinfo ( $params )
	{
		$params['meta'] = "filerepoinfo";
		$result = $this->exchange_query ( $params );
		if ( $result )
			$result = $result['filerepoinfo'];
		return $result;
	}


	protected function exchange_globaluserinfo ( $params )
	{
		$params['meta'] = "globaluserinfo";
		$result = $this->exchange_query ( $params );
		if ( $result )
			$result = $result['globaluserinfo'];
		return $result;
	}


	protected function exchange_paraminfo ( $params )
	{
		return $this->exchange ( "paraminfo", $params );
	}


# ---------------------------------------------------------------------------- #
# --               Reading / Writing info from / to disk                    -- #
# ---------------------------------------------------------------------------- #


	protected function read_generalinfo ()
	{
		return $this->read_infofile ( "general" );
	}

	protected function write_generalinfo ( $info )
	{
		return $this->write_infofile ( "general", $info );
	}

	protected function read_paraminfo ()
	{
		return $this->read_infofile ( "param" );
	}

	protected function write_paraminfo ( $info )
	{
		return $this->write_infofile ( "param", $info );
	}

	protected function read_siteinfo ()
	{
		return $this->read_infofile ( "site" );
	}

	protected function write_siteinfo ( $info )
	{
		return $this->write_infofile ( "site", $info );
	}

	protected function read_userinfo ()
	{
		return $this->read_infofile ( "user" );
	}

	protected function write_userinfo ( $info )
	{
		return $this->write_infofile ( "user", $info );
	}

	protected function read_allmessages ()
	{
		return $this->read_infofile ( "allmessages" );
	}

	protected function write_allmessages ( $info )
	{
		return $this->write_infofile ( "allmessages", $info );
	}

	protected function read_filerepoinfo ()
	{
		return $this->read_infofile ( "filerepo" );
	}

	protected function write_filerepoinfo ( $info )
	{
		return $this->write_infofile ( "filerepo", $info );
	}

	protected function read_globaluserinfo ()
	{
		return $this->read_infofile ( "globaluser" );
	}

	protected function write_globaluserinfo ( $info )
	{
		return $this->write_infofile ( "globaluser", $info );
	}


# ---------------------------------------------------------------------------- #
# --                       Loading and clearing info                        -- #
# ---------------------------------------------------------------------------- #


	protected function load_all_info ()
	{
		$this->load_info ( 'general' );
		$this->load_info ( 'param' );
		$this->load_info ( 'site' );
		$this->load_info ( 'user' );
		$this->load_info ( 'allmessages' );
		$this->load_info ( 'filerepo' );
		$this->load_info ( 'globaluser' );
	}


# ---------------------------------------------------------------------------- #
# --                       Providing info presence                          -- #
# ---------------------------------------------------------------------------- #


	# ----- Indexing / Unindexing info ----- #

	private function param_namekey_module ( &$module, $name = NULL )
	{
		if ( ! is_array ( $module ) || isset ( $module['missing'] ) )
			return false;

		if ( ! isset ( $module['name'] ) )
			$module['name'] = $name;

		if ( isset ( $module['parameters'] ) )
		{
			$params = array();
			foreach ( $module['parameters'] as $param_desc )
				$params[$param_desc['name']] = $param_desc;
			$module['parameters'] = $params;
		}

		if ( isset ( $module['errors'] ) )
		{
			$errors = array();
			foreach ( $module['errors'] as $error_desc )
				if ( is_array ( $error_desc ) && isset ( $error_desc['code'] ) )
				{
					$errors[$error_desc['code']] = $error_desc;
				}
				else
				{
					$this->log ( "In module '" . $module['name'] . "' error descriptions" .
						" there is a string '" . $error_desc .
						"' instead of an array ( 'code' => ..., 'desc' => ... ) - " .
						"possible MediaWiki API bug?", LL_DEBUG );
					$errors[] = $error_desc;
				}
			$module['errors'] = $errors;
		}
		return true;
	}

	protected function param_namekey_modules ( &$modules_array )
	{
		foreach ( $modules_array as $key => &$module )
		{
			if ( ! isset ( $module['missing'] ) )
				$this->param_namekey_module ( $module );
			unset ( $modules_array[$key] );
			$modules_array[$module['name']] = $module;
		}
	}

	protected function index_param_module ( &$paraminfo, $module, $name = NULL )
	{
		$this->param_namekey_module ( $module, $name );
		$paraminfo[$module['name']] = $module;
	}

	protected function index_param_modules ( &$paraminfo, $modules )
	{
		foreach ( $modules as $module )
			$this->index_param_module ( $paraminfo, $module );
	}


	private function index_siteinfo ( &$siteinfo )
	{
		$siteindex = &$this->index['site'];

		if ( is_array ( $siteinfo['namespaces'] ) )
		{
			$siteindex['namespaces_by_names'] = array();
			$siteindex['namespaces_allnames'] = array();
			foreach ( $siteinfo['namespaces'] as &$namespace )
			{
				$siteindex['namespaces_by_names'][$namespace['*']] = &$namespace;
				$siteindex['namespaces_allnames'][$namespace['id']] = array ( &$namespace['*'] );
				if ( isset ( $namespace['canonical'] ) )
				{
					$siteindex['namespaces_by_names'][$namespace['canonical']] = &$namespace;
					if ( ! in_array ( $namespace['canonical'], $siteindex['namespaces_allnames'][$namespace['id']] ) )
						$siteindex['namespaces_allnames'][$namespace['id']][] = &$namespace['canonical'];
				}
			}
		}

		if ( is_array ( $siteinfo['namespacealiases'] ) )
		{
			foreach ( $siteinfo['namespacealiases'] as $namespacealias )
			{
				$siteindex['namespaces_by_names'][$namespacealias['*']] =
					&$siteinfo['namespaces'][$namespacealias['id']];
				if ( ! in_array ( $namespacealias['*'], $siteindex['namespaces_allnames'][$namespacealias['id']] ) )
					$siteindex['namespaces_allnames'][$namespacealias['id']][] =
						&$namespacealias['*'];
			}
		}

		if ( is_array ( $siteinfo['specialpagealiases'] ) )
		{
			$siteindex['specialpagealiases_allnames'] = array();
			foreach ( $siteinfo['specialpagealiases'] as &$specialpagealias )
			{
				$siteindex['specialpagealiases_allnames'][$specialpagealias['realname']] = &$specialpagealias;
				if ( is_array ( $specialpagealias['aliases'] ) )
					foreach ( $specialpagealias['aliases'] as $alias )
						$siteindex['specialpagealiases_allnames'][$alias] = &$specialpagealias;
			}
		}

		if ( is_array ( $siteinfo['interwikimap'] ) )
		{
			$siteindex['interwikimap_by_prefix'] = array();
			$siteindex['interwikimap_by_url'] = array();
			$siteindex['interwikimap_by_language'] = array();
			$siteindex['interwikimap_by_wikiid'] = array();
			$siteindex['interwikimap_by_api'] = array();
			foreach ( $siteinfo['interwikimap'] as &$interwiki )
			{
				if ( isset ( $interwiki['prefix'] ) )
					$siteindex['interwikimap_by_prefix'][$interwiki['prefix']] = &$interwiki;
				if ( isset ( $interwiki['url'] ) )
					$siteindex['interwikimap_by_url'   ][$interwiki['url'   ]] = &$interwiki;
				if ( isset ( $interwiki['language'] ) )
				{
					if ( ! isset ( $siteindex['interwikimap_by_language'][$interwiki['language']] ) )
						$siteindex['interwikimap_by_language'][$interwiki['language']] = array();
					$siteindex['interwikimap_by_language'][$interwiki['language']][] = &$interwiki;
				}
				if ( isset ( $interwiki['wikiid'] ) )
					$siteindex['interwikimap_by_wikiid'][$interwiki['wikiid']] = &$interwiki;
				if ( isset ( $interwiki['api'] ) )
					$siteindex['interwikimap_by_api'][$interwiki['api']] = &$interwiki;
			}
		}

		if ( is_array ( $siteinfo['magicwords'] ) )
		{
			$siteindex['magicwords_by_name'] = array();
			$siteindex['magicwords_by_alias'] = array();
			foreach ( $siteinfo['magicwords'] as &$magicword )
			{
				$siteindex['magicwords_by_name'][$magicword['name']] = &$magicword;
				if ( is_array ( $magicword['aliases'] ) )
					foreach ( $magicword['aliases'] as $alias )
						$siteindex['magicwords_by_alias'][$alias] = &$magicword;
			}
		}

		if ( is_array ( $siteinfo['languages'] ) )
		{
			$siteindex['languages_by_code'] = array();
			$siteindex['languages_by_language'] = array();
			foreach ( $siteinfo['languages'] as &$language )
			{
				$siteindex['languages_by_code'][$language['code']] = &$language;
				$siteindex['languages_by_language'][$language['*']] = &$language;
			}
		}

		if ( is_array ( $siteinfo['dbrepllag'] ) )
		{
			$siteindex['dbrepllag_by_host'] = array();
			foreach ( $siteinfo['dbrepllag'] as &$hostlag )
				$siteindex['dbrepllag_by_host'][$hostlag['host']] = &$hostlag;
		}

		if ( is_array ( $siteinfo['fileextensions'] ) )
		{
			$siteindex['fileextensions_by_ext'] = array();
			foreach ( $siteinfo['fileextensions'] as &$extension )
				$siteindex['fileextensions_by_ext'][$extension['ext']] = &$extension;
		}

		if ( is_array ( $siteinfo['usergroups'] ) )
		{
			$siteindex['usergroups_by_name'] = array();
			foreach ( $siteinfo['usergroups'] as &$group )
				$siteindex['usergroups_by_name'][$group['name']] = &$group;
		}

		if ( is_array ( $siteinfo['extensions'] ) )
		{
			$siteindex['extensions_by_name'] = array();
			$siteindex['extensions_by_descriptionmsg'] = array();
			foreach ( $siteinfo['extensions'] as &$extension )
			{
				$siteindex['extensions_by_name'][$extension['name']] = &$extension;
				if ( isset ( $extension['descriptionmsg'] ) )
					$siteindex['extensions_by_descriptionmsg'][$extension['descriptionmsg']] = &$extension;
			}
		}

		if ( isset ( $siteinfo['skins'] ) && is_array ( $siteinfo['skins'] ) )
		{
			$siteindex['skins_by_code'] = array();
			$siteindex['skins_by_name'] = array();
			foreach ( $siteinfo['skins'] as &$skin )
			{
				$siteindex['skins_by_code'][$skin['code']] = &$skin;
				$siteindex['skins_by_name'][$skin['*']] = &$skin;
			}
		}

		if ( isset ( $siteinfo['showhooks'] ) && is_array ( $siteinfo['showhooks'] ) )
		{
			$siteindex['showhooks_by_name'] = array();
			foreach ( $siteinfo['showhooks'] as &$group )
				$siteindex['showhooks_by_name'][$group['name']] = &$group;
		}

	}


	private function index_allmessages ( &$allmessages )
	{
		$reindexed = array();
		foreach ( $allmessages as $key => $message )
			$reindexed[$message['name']] = $message;
		$allmessages = $reindexed;
	}


	private function index_filerepoinfo ( &$filerepoinfo )
	{
		$reindexed = array();
		foreach ( $filerepoinfo as $key => $message )
			$reindexed[$message['name']] = $message;
		$filerepoinfo = $reindexed;
	}


	private function index_globaluserinfo ( &$globaluserinfo )
	{
		$reindexed = array();
		foreach ( $globaluserinfo['merged'] as $key => $merge )
			$reindexed[$merge['wiki']] = $merge;
		$globaluserinfo['merged'] = $reindexed;
	}


	# ----- Fetching paraminfo structures ----- #


	private function fetch_paraminfo_piece ( $module_name, $submodules = array() )
	{
		$submodules_arrays = array_chunk ( $submodules, 50 );
		if ( empty ( $submodules_arrays ) )
			$submodules_arrays[] = array();

		$result = array();

		foreach ( $submodules_arrays as $array )
		{
			$submodules_list = implode ( '|', $array );
			$params = array ( $module_name => $submodules_list );

			$data = $this->exchange_paraminfo ( $params );
			if ( is_array ( $data ) && isset ( $data[$module_name] ) &&
				! isset ( $data[$module_name]['missing'] ) )

				$result = array_merge ( $result, $data[$module_name] );
		}

		return $result;
	}

	protected function fetch_paraminfo_with_separate ( $module_name, $submodules )
	{
		$paraminfo = $this->fetch_paraminfo_piece ( $module_name, $submodules );
		if ( ( $paraminfo === false ) && ( count ( $submodules ) > 1 ) )
		{
			$this->log ( "Trying to re-fetch the modules paraminfo separately...",
				LL_INFO );
			$paraminfo = array();
			foreach ( $submodules as $submodule )
			{
				$module = $this->fetch_paraminfo_piece ( $submodule );
				if ( $module === false )
					$this->log ( "Could not fetch paraminfo for module " . $submodule,
						LL_ERROR );
				else
					$this->index_param_module ( $paraminfo, $module, $submodule );
			}
		}

		return $paraminfo;
	}

	protected function fetch_param_mainmodule ()
	{
		$paraminfo = $this->fetch_paraminfo_piece ( 'mainmodule' );
		if ( empty ( $paraminfo ) )  // that syntax was deprecated in newer APIs
		{
			$paraminfo = $this->fetch_paraminfo_piece ( 'modules', array ( "main" ) );
			$paraminfo = reset ( $paraminfo );
			$paraminfo['name'] = "mainmodule"; // would be 'main' in this case
		}

		return $paraminfo;
	}

	protected function fetch_param_pagesetmodule ()
	{
		return $this->fetch_paraminfo_piece ( 'pagesetmodule' );
		// deprecated in newer APIs - will return an empty array
	}

	protected function fetch_param_modules ( $modulenames_array )
	{
		return $this->fetch_paraminfo_with_separate (
			'modules', $modulenames_array );
	}

	protected function fetch_param_querymodules ( $modulenames_array )
	{
		return $this->fetch_paraminfo_with_separate (
			'querymodules', $modulenames_array );
	}

	protected function fetch_paraminfo ()
	{
		$paraminfo = array();

		$mainmodule = $this->fetch_param_mainmodule();
		if ( is_array ( $mainmodule ) && ! empty ( $mainmodule ) )
			$this->index_param_module ( $paraminfo, $mainmodule, 'mainmodule' );

		$pagesetmodule = $this->fetch_param_pagesetmodule();
		// don't index it early - might need to reconstruct it from query module info
		// (newer APIs obsolete it)

		if ( $mainmodule )
			$modules_array = $paraminfo['mainmodule']['parameters']['action']['type'];
		else
			$modules_array = array (
				"query", "login", "logout", "edit", "move", "delete", "undelete",
				"rollback", "protect", "block", "unblock", "watch", "emailuser",
				"patrol", "import", "expandtemplates", "parse", "upload",
				"purge", "userrights",
			);

		# short-cirquit around MW 1.16 bug - returns error if requested userrights
		$mw_verno = $this->wiki_version_number();
		if ( ( $mw_verno >= 11600 ) && ( $mw_verno < 11700 ) )
			$modules_array = array_diff ( $modules_array, array ( "userrights" ) );

		$modules = $this->fetch_param_modules ( $modules_array );
		if ( is_array ( $modules ) )
		{
			$paraminfo['modules'] = array();
			$this->index_param_modules ( $paraminfo['modules'], $modules );

			if ( empty ( $pagesetmodule ) )  // a newer API obsoleted it
			{
				$pagesetmodule = array (
					'name' => "pagesetmodule",
					'prefix' => "",
					'readrights' => "",
					'parameters' => array()
				);
				$pagesetmodule_params = array ( 'titles', 'pageids', 'revids',
					'redirects', 'converttitles' );
				foreach ( $pagesetmodule_params as $param )
				{
					if ( isset ( $paraminfo['modules']['query']['parameters'][$param] ) )
					{
						$pagesetmodule['parameters'][$param] =
							$paraminfo['modules']['query']['parameters'][$param];
						unset ( $paraminfo['modules']['query']['parameters'][$param] );
					}
				}
			}
		}

		if ( is_array ( $paraminfo['modules'] ) &&
			array_key_exists ( 'query', $paraminfo['modules'] ) )

			$querymodules_array = array_unique ( array_merge (
					$paraminfo['modules']['query']['parameters']['list']['type'],
					$paraminfo['modules']['query']['parameters']['generator']['type'],
					$paraminfo['modules']['query']['parameters']['prop']['type'],
					$paraminfo['modules']['query']['parameters']['meta']['type']
			) );
		else
			$querymodules_array = array (
				"info", "revisions", "links", "langlinks", "images", "imageinfo",
				"stashimageinfo", "templates", "categories", "extlinks", "categoryinfo",
				"duplicatefiles", "globalusage",
				"allimages", "allpages", "alllinks", "allcategories", "allusers",
				"backlinks", "blocks", "categorymembers", "deletedrevs", "embeddedin",
				"imageusage", "logevents", "recentchanges", "search", "tags",
				"usercontribs", "watchlist", "watchlistraw", "exturlusage", "users",
				"random", "protectedtitles", "globalblocks",
				"siteinfo", "userinfo", "allmessages", "filerepoinfo", "globaluserinfo",
			);

		$querymodules = $this->fetch_param_querymodules ( $querymodules_array );
		if ( is_array ( $querymodules ) )
		{
			$paraminfo['querymodules'] = array();
			$this->index_param_modules ( $paraminfo['querymodules'], $querymodules );
		}

		if ( is_array ( $pagesetmodule ) && ! empty ( $pagesetmodule ) )
			$this->index_param_module ( $paraminfo, $pagesetmodule, 'pagesetmodule' );

		if ( isset ( $paraminfo['paraminfo']['parameters'] ) )
			foreach ( array_keys ( $paraminfo['paraminfo']['parameters'] ) as $paraminfo_param )
				if ( ! in_array ( $paraminfo_param, array ( 'mainmodule', 'pagesetmodule', 'modules', 'querymodules' ) ) )
					$this->log ( "Don't know how to process paraminfo about " . $paraminfo_param . " - ignoring it!", LL_WARNING );

		return $paraminfo;
	}


	# ----- Fetching and storing info from a MediaWiki ----- #

	protected function should_fetch ( $type )
	{
		if ( ! empty ( $this->info[$type] ) || ! $this->autoload )
			return false;
		if ( isset ( $this->info_settings['infotypes'][$type]['fetch'] ) )
			$fetch = $this->info_settings['infotypes'][$type]['fetch'];
		else
			$fetch = "if_unknown";

		switch ( $fetch )
		{
			case "always" :
				return true;

			case "never" :
				return false;

			case "if_unknown" :
			case "if_missing" :
				return ( ! $this->exists_infofile ( $type ) );

			case "if_older_than" :
					if ( ! isset ( $this->info_settings['infotypes'][$type]['days'] ) )
					$this->info_settings['infotypes'][$type]['days'] = 30;
				return ( ! $this->exists_infofile ( $type ) ||
					( $this->mtime_infofile ( $type ) <
						( time() - ( $this->info_settings['infotypes'][$type]['days'] * 86400 ) ) ) );

			case "on_newversion" :
				if ( ! $this->exists_infofile ( $type ) )
					return true;
				return ( ! isset ( $this->old_info['generator'] ) ||
					$this->old_info['generator'] !== $this->info['general']['generator'] );

			case "on_newrevision" :
				if ( ! $this->exists_infofile ( $type ) )
					return true;

				if ( isset ( $this->info['general']['rev'] ) )
					return ( ! isset ( $this->old_info['rev'] ) ||
						( $this->old_info['rev'] !== $this->info['general']['rev'] ) );

				elseif ( isset ( $this->info['general']['git-hash'] ) )
					return ( ! isset ( $this->old_info['git-hash'] ) ||
						( $this->old_info['git-hash'] !== $this->info['general']['git-hash'] ) );

				else
				{
					$this->info_settings['infotypes'][$type]['fetch'] = "on_newversion";
					$this->log ( "General info contains no revision or git-hash - " .
						"will fetch " . $type . " info on new version!", LL_DEBUG );
					return $this->should_fetch ( $type );
				}

			default :
				throw new ApibotException_InternalError (
					"Bad parameter to Info->should_fetch()" );
		}
	}


	protected function load_generalinfo ()
	{
		if ( empty ( $this->old_info ) )
		{
			$old = $this->read_generalinfo();
			if ( is_array ( $old ) )
			{
				$this->old_info['generator'] = $old['generator'];
				if ( isset ( $old['rev'] ) )
					$this->old_info['rev'] = $old['rev'];
				if ( isset ( $old['git-hash'] ) )
					$this->old_info['git-hash'] = $old['git-hash'];
			}
		}

		if ( $this->should_fetch ( "general" ) )
		{
			$this->log ( "Fetching general info for " . $this->infostore->sitename() .
				"...", LL_DEBUG );

			$result = $this->exchange_generalinfo();
			if ( $result )
			{
				$this->info['general'] = $result;

				if ( ! empty ( $this->info['general'] ) )
				{
					if ( isset ( $this->info['general']['time'] ) )
						$this->info['general']['timediff'] =
							strtotime ( $this->info['general']['time'] ) -
								$this->exchanger->last_time();
					else
						$this->info['general']['timediff'] = NULL;

					$this->write_generalinfo ( $this->info['general'] );
					return true;
				}
			}
			return false;
		}

		$this->info['general'] = $this->read_generalinfo();
		return ( ! empty ( $this->info['general'] ) );
	}

	protected function load_paraminfo ()
	{
		if ( $this->should_fetch ( "param" ) )
		{
			$this->log ( "Fetching paraminfo for " . $this->infostore->sitename() .
				"...", LL_DEBUG );

			$this->info['param'] = $this->fetch_paraminfo();
			if ( is_array ( $this->info['param'] ) )
			{
				$this->write_paraminfo ( $this->info['param'] );
				$this->exchanger->set_info ( $this );
				return true;
			}
			return false;
		}

		$this->info['param'] = $this->read_paraminfo();
		$this->exchanger->set_info ( $this );
		return ( ! empty ( $this->info['param'] ) );
	}

	protected function load_siteinfo ()
	{
		if ( $this->should_fetch ( "site" ) )
		{
			$this->log ( "Fetching siteinfo for " . $this->infostore->sitename() .
				"...", LL_DEBUG );

			if ( is_array ( $this->info['param']['querymodules']['siteinfo']['parameters']['prop']['type'] ) )
				$props = implode ( '|', $this->info['param']['querymodules']['siteinfo']['parameters']['prop']['type'] );
			else
				$props = "namespaces|statistics|interwikimap|dbrepllag";

			$params = array ( 'siprop' => $props );
			if ( isset ( $this->info['param']['querymodules']['siteinfo']['parameters']['showalldb'] ) )
				$params['sishowalldb'] = "";
			if ( isset ( $this->info['param']['querymodules']['siteinfo']['parameters']['numberingroup'] ) )
				$params['sinumberingroup'] = "";

			while ( true )
			{
				try
				{
					$result = $this->exchange_siteinfo ( $params );
					break;
				}
				catch ( Exception $e )
				{
					if ( ( $e->code == "siincludeAllDenied" ) )
					{
						$this->log ( "All servers siteinfo denied - will go without it...",
							LL_DEBUG );
						unset ( $params['sishowalldb'] );
					}
				}
			}

			if ( $result )
			{
				$this->info['site'] = $result;
				if ( is_array ( $this->info['site'] ) )
				{
					$this->index_siteinfo ( $this->info['site'] );
					$this->write_siteinfo ( $this->info['site'] );
					return true;
				}
			}
		}

		$this->info['site'] = $this->read_siteinfo();
		if ( is_array ( $this->info['site'] ) )
			$this->index_siteinfo ( $this->info['site'] );
		return ( ! empty ( $this->info['site'] ) );
	}

	protected function load_userinfo ()
	{
		if ( $this->should_fetch ( "user" ) )
		{
			$this->log ( "Fetching userinfo for " . $this->infostore->siteuser() .
				"...", LL_DEBUG );

			if ( is_array ( $this->info['param']['querymodules']['userinfo']['parameters']['prop']['type'] ) )
				$props = implode ( '|', $this->info['param']['querymodules']['userinfo']['parameters']['prop']['type'] );
			else
				$props = "blockinfo|hasmsg|groups|rights";

			$params = array ( 'uiprop' => $props );
			$result = $this->exchange_userinfo ( $params );
			if ( $result )
			{
				$this->info['user'] = $result;
				if ( is_array ( $this->info['user'] ) )
				{
					$this->write_userinfo ( $this->info['user'] );
					return true;
				}
			}
			return false;
		}

		$this->info['user'] = $this->read_userinfo();
		return ( ! empty ( $this->info['user'] ) );
	}

	protected function load_allmessages ()
	{
		if ( $this->should_fetch ( "allmessages" ) )
		{
			$this->log ( "Fetching allmessages for " . $this->infostore->sitename() .
				"...", LL_DEBUG );

			$params = array ( 'ammessages' => "*" );
			$result = $this->exchange_allmessages ( $params );
			if ( $result )
			{
				$this->info['allmessages'] = $result;
				if ( is_array ( $this->info['allmessages'] ) )
				{
					$this->index_allmessages ( $this->info['allmessages'] );
					$this->write_allmessages ( $this->info['allmessages'] );
					return true;
				}
			}
			return false;
		}

		$this->info['allmessages'] = $this->read_allmessages();
		return ( ! empty ( $this->info['allmessages'] ) );
	}

	protected function load_filerepoinfo ()
	{
		if ( $this->should_fetch ( "filerepo" ) )
		{
			$this->log ( "Fetching filerepoinfo for " . $this->infostore->sitename() .
				"...", LL_DEBUG );

			if ( is_array ( $this->info['param']['querymodules']['filerepoinfo']['parameters']['prop']['type'] ) )
				$props = implode ( '|', $this->info['param']['querymodules']['filerepoinfo']['parameters']['prop']['type'] );
			else
				$props = "apiurl|name|displayname|rooturl|local";

			$params = array ( 'friprop' => $props );
			$result = $this->exchange_filerepoinfo ( $params );
			if ( $result )
			{
				$this->info['filerepo'] = $result;
				if ( is_array ( $this->info['filerepo'] ) )
				{
					$this->index_filerepoinfo ( $this->info['filerepo'] );
					$this->write_filerepoinfo ( $this->info['filerepo'] );
					return true;
				}
			}
			return false;
		}

		$this->info['filerepo'] = $this->read_filerepoinfo();
		return ( ! empty ( $this->info['filerepo'] ) );
	}

	protected function load_globaluserinfo ()
	{
		if ( $this->should_fetch ( "globaluser" ) )
		{
			$this->log ( "Fetching globaluserinfo for " . $this->infostore->siteuser() .
				"...", LL_DEBUG );

			$props = implode ( '|', $this->info['param']['querymodules']['globaluserinfo']['parameters']['prop']['type'] );

			$params = array ( 'guiprop' => $props );
			$result = $this->exchange_globaluserinfo ( $params );
			if ( $result )
			{
				$this->info['globaluser'] = $results['globaluserinfo'];
				if ( is_array ( $this->info['globaluser'] ) )
				{
					$this->index_globaluserinfo ( $this->info['globaluser'] );
					$this->write_globaluserinfo ( $this->info['globaluser'] );
					return true;
				}
			}
			return false;
		}

		$this->info['globaluser'] = $this->read_globaluserinfo();
		return ( ! empty ( $this->info['globaluser'] ) );
	}


	# ----- Implemented ----- #


	public function nohooks__load_info ( $hook_object, $type )
	{
		if ( ! $this->identity->logged_in() )
			$this->identity->login();

		switch ( $type )
		{
			case "general" :
				return $this->load_generalinfo();

			case "param" :
				if ( ! isset ( $this->info['general'] ) )
					$this->load_info ( 'general' );
				if ( $this->wiki_version_number() >= 11200 )
					return $this->load_paraminfo();
				else
					return false;

			case "site" :
				if ( ! isset ( $this->info['param'] ) )
					$this->load_info ( 'param' );
				if ( isset ( $this->info['param'] ) && is_array ( $this->info['param'] ) )
					return $this->load_siteinfo();
				return false;

			case "user" :
				if ( ! isset ( $this->info['param'] ) )
					$this->load_info( 'param' );
				if ( isset ( $this->info['param'] ) && is_array ( $this->info['param'] ) )
					return $this->load_userinfo();
				return false;

			case "allmessages" :
				if ( ! isset ( $this->info['general'] ) )
					$this->load_generalinfo();
				if ( $this->site_generator_version() >= 1.12 )
					return $this->load_allmessages();
				else
					return false;

			case "filerepo" :
				if ( ! isset ( $this->info['general'] ) )
					$this->load_generalinfo();
				if ( $this->site_generator_version() >= 1.22 )
					return $this->load_filerepoinfo();
				else
					return false;

			case "globaluser" :
				if ( ! isset ( $this->info['param'] ) )
					$this->load_info( 'param' );
				if ( isset ( $this->info['param'] ) && is_array ( $this->info['param'] ) )
					return $this->load_globaluserinfo();
				return false;
		}
	}


	protected function backend_name ()
	{
		return "API";
	}


	protected function default_info_settings ()
	{
		return array (
			'general'     => array ( 'fetch' => "always"         ),
			'param'       => array ( 'fetch' => "on_newrevision" ),
			'site'        => array ( 'fetch' => "on_newrevision" ),
			'user'        => array ( 'fetch' => "on_newversion"  ),
			'allmessages' => array ( 'fetch' => "on_newrevision" ),
			'filerepo'    => array ( 'fetch' => "on_newrevision" ),
			'globaluser'  => array ( 'fetch' => "on_newversion"  ),
		);
	}


# ---------------------------------------------------------------------------- #
# --                                                                        -- #
# --                         Info direct access                             -- #
# --                                                                        -- #
# ---------------------------------------------------------------------------- #


# ---------------------------------------------------------------------------- #
# --                           General info                                 -- #
# ---------------------------------------------------------------------------- #


	public function general_info ()
	{
		return $this->infotype ( "general" );
	}

	public function general_info_isset ()
	{
		return $this->infotype_isset ( 'general' );
	}

	public function general_element ( $key )
	{
		return $this->infotype_element ( 'general', $key );
	}

	public function general_element_isset ( $key )
	{
		return $this->infotype_element_isset ( 'general', $key );
	}


	public function site_mainpage ()
	{
		return $this->general_element ( 'mainpage' );
	}

	public function site_base ()
	{
		return $this->general_element ( 'base' );
	}

	public function site_name ()
	{
		return $this->general_element ( 'sitename' );
	}

	public function site_generator ()
	{
		return $this->general_element ( 'generator' );
	}

	public function site_phpversion ()
	{
		return $this->general_element ( 'phpversion' );
	}

	public function site_phpsapi ()
	{
		return $this->general_element ( 'phpsapi' );
	}

	public function site_dbversion ()
	{
		return $this->general_element ( 'dbversion' );
	}

	public function site_dbtype ()
	{
		return $this->general_element ( 'dbtype' );
	}

	public function site_revision ()
	{
		return $this->general_element ( 'rev' );
	}

	public function site_case ()
	{
		return $this->general_element ( 'case' );
	}

	public function site_rights ()
	{
		return $this->general_element ( 'rights' );
	}

	public function site_lang ()
	{
		return $this->general_element ( 'lang' );
	}

	public function site_fallback ()
	{
		return $this->general_element ( 'fallback' );
	}

	public function site_fallback8bitEncoding ()
	{
		return $this->general_element ( 'fallback8bitEncoding' );
	}

	public function site_writeapi ()
	{
		return $this->general_element_isset ( 'writeapi' );
	}

	public function site_timezone ()
	{
		return $this->general_element ( 'timezone' );
	}

	public function site_timeoffset ()
	{
		return $this->general_element ( 'timeoffset' );
	}

	public function site_articlepath ()
	{
		return $this->general_element ( 'articlepath' );
	}

	public function site_scriptpath ()
	{
		return $this->general_element ( 'scriptpath' );
	}

	public function site_script ()
	{
		return $this->general_element ( 'script' );
	}

	public function site_variantarticlepath ()
	{
		return $this->general_element ( 'variantarticlepath' );
	}

	public function site_server ()
	{
		return $this->general_element ( 'server' );
	}

	public function site_wikiid ()
	{
		return $this->general_element ( 'wikiid' );
	}

	public function site_time ()
	{
		return $this->general_element ( 'time' );
	}

	public function site_misermode ()
	{
		return $this->general_element_isset ( 'misermode' );
	}


	public function site_timediff ()
	{
		return $this->general_element ( 'timediff' );
	}


	# --- Time-related --- #

	public function remote_time ( $time = NULL )
	{
		if ( is_null ( $time ) )
			$time = time();

		$timediff = $this->site_timediff();
		if ( is_null ( $timediff ) )
			return NULL;
		else
			return $time + $this->site_timediff();
	}


	# --- Site MediaWiki versions --- #

	public function site_generator_version ()
	{
		$generator = $this->site_generator();
		if ( preg_match ( '/\d\.\d\d?(\.\d\d?)?/u', $generator, $matches ) )
			return $matches[0];
		return false;
	}

	public function wiki_version_number ()
	{
		if ( preg_match ( '/(\d)\.(\d\d?)(\.(\d\d?))?/u',
			$this->site_generator_version(), $matches ) )
		{
			$number = ( (integer)$matches[1] * 10000 ) + ( (integer)$matches[2] * 100 );
			if ( isset ( $matches[3] ) )
				$number += (integer)$matches[3];
			return $number;
		}
		return NULL;
	}


# ---------------------------------------------------------------------------- #
# --                              Site info                                 -- #
# ---------------------------------------------------------------------------- #


	# --- Generic access --- #

	public function site_info ()
	{
		return $this->infotype ( "site" );
	}

	public function site_info_isset ()
	{
		return $this->infotype_isset ( 'site' );
	}


	public function site_elements_names ()
	{
		$site = $this->site_info();
		return $this->element_arraykeys ( $site );
	}

	public function site_known_elements_names ()
	{
		return array (
			'general',
			'namespaces',
			'namespacealiases',
			'usergroups',
			'specialpagealiases',
			'magicwords',
			'interwikimap',
			'fileextensions',
			'rightsinfo',
			'dbrepllag',
			'statistics',
			'skins',
			'extensions',
			'extensiontags',
			'functionhooks',
			'showhooks',
		);
	}

	public function site_unknown_elements_names ()
	{
		$site_elements_names = $this->site_elements_names();
		return ( is_array ( $site_elements_names )
			? array_diff ( $site_elements_names, $this->site_known_elements_names() )
			: NULL );
	}


	public function site_element ( $key )
	{
		return $this->infotype_element ( 'site', $key );
	}

	public function site_element_isset ( $key )
	{
		return $this->infotype_element_isset ( 'site', $key );
	}

	public function site_element_subs_count ( $key )
	{
		return $this->infotype_element_subs_count ( 'site', $key );
	}

	public function site_element_arraykeys ( $key )
	{
		return $this->infotype_element_arraykeys ( 'site', $key );
	}

	public function site_sub ( $key, $subkey )
	{
		return $this->infotype_sub ( 'site', $key, $subkey );
	}


	# Indexes are known in advance - no sense in generic public access to them

	public function siteindex ( $indexname )
	{
		return $this->indextype_element ( 'site', $indexname );
	}

	public function siteindex_element ( $indexname, $key )
	{
		$index = $this->siteindex ( $indexname );
		return $this->element_sub ( $index, $key );
	}

	public function siteindex_element_isset ( $indexname, $key )
	{
		$index = $this->siteindex ( $indexname );
		return $this->element_sub_isset ( $index, $key );
	}

	protected function siteindex_element_arraykeys ( $indexname )
	{
		$index = $this->siteindex ( $indexname );
		return $this->element_arraykeys ( $index );
	}

	protected function siteindex_sub ( $indexname, $element_key )
	{
		$index = $this->siteindex ( $indexname );
		return $this->element_sub ( $index, $element_key );
	}


	# --- Namespaces --- #

	private function _namespace_exists ( $namespace )
	{
		return is_array ( $namespace );
	}

	private function _namespace_id ( $namespace )
	{
		return $this->element_sub ( $namespace, 'id' );
	}

	private function _namespace_case ( $namespace )
	{
		return $this->element_sub ( $namespace, 'case' );
	}

	private function _namespace_basic_name ( $namespace )
	{
		return $this->element_sub ( $namespace, '*' );
	}

	private function _namespace_canonical_name ( $namespace )
	{
		return $this->element_sub ( $namespace, 'canonical' );
	}

	private function _namespace_allows_subpages ( $namespace )
	{
		return $this->element_sub_isset ( $namespace, 'subpages' );
	}

	private function _namespace_allnames ( $namespace )
	{
		return ( is_array ( $namespace )
			? $this->siteindex_sub ( 'namespaces_allnames', $this->_namespace_id ( $namespace ) )
			: NULL );
	}


	public function namespaces_are_present ()
	{
		return $this->site_element_isset ( 'namespaces' );
	}

	public function namespaces ()
	{
		return $this->site_element ( 'namespaces' );
	}

	public function namespaces_count ()
	{
		return $this->site_element_subs_count ( 'namespaces' );
	}

	public function namespaces_ids ()
	{
		return $this->site_element_arraykeys ( 'namespaces' );
	}

	public function namespaces_basic_names ()
	{
		return $this->site_subsubs_by_key ( 'namespaces', '*' );
	}

	public function namespaces_canonical_names ()
	{
		return $this->site_subsubs_by_key ( 'namespaces', 'canonical' );
	}

	public function namespaces_allnames ()
	{
		return $this->siteindex_element_arraykeys ( 'namespaces_by_names' );
	}


	public function namespace_by_id ( $id )
	{
		return $this->site_sub ( 'namespaces', $id );
	}

	public function namespace_exists_by_id ( $id )
	{
		return $this->_namespace_exists ( $this->namespace_by_id ( $id ) );
	}

	public function namespace_case_by_id ( $id )
	{
		return $this->_namespace_case ( $this->namespace_by_id ( $id ) );
	}

	public function namespace_basic_name_by_id ( $id )
	{
		return $this->_namespace_basic_name ( $this->namespace_by_id ( $id ) );
	}

	public function namespace_canonical_name_by_id ( $id )
	{
		return $this->_namespace_canonical_name ( $this->namespace_by_id ( $id ) );
	}

	public function namespace_allows_subpages_by_id ( $id )
	{
		return $this->_namespace_allows_subpages ( $this->namespace_by_id ( $id ) );
	}

	public function namespace_allnames_by_id ( $id )
	{
		return $this->_namespace_allnames ( $this->namespace_by_id ( $id ) );
	}


	public function namespace_by_name ( $name )
	{
		return $this->siteindex_sub ( 'namespaces_by_names', $name );
	}

	public function namespace_exists_by_name ( $name )
	{
		return $this->_namespace_exists ( $this->namespace_by_name ( $name ) );
	}

	public function namespace_id_by_name ( $name )
	{
		return $this->_namespace_id ( $this->namespace_by_name ( $name ) );
	}

	public function namespace_case_by_name ( $name )
	{
		return $this->_namespace_case ( $this->namespace_by_name ( $name ) );
	}

	public function namespace_basic_name_by_name ( $name )
	{
		return $this->_namespace_basic_name ( $this->namespace_by_name ( $name ) );
	}

	public function namespace_canonical_name_by_name ( $name )
	{
		return $this->_namespace_canonical_name ( $this->namespace_by_name ( $name ) );
	}

	public function namespace_allows_subpages_by_name ( $name )
	{
		return $this->_namespace_allows_subpages ( $this->namespace_by_name ( $name ) );
	}

	public function namespace_allnames_by_name ( $name )
	{
		return $this->_namespace_allnames ( $this->namespace_by_name ( $name ) );
	}


	public function namespace_by_id_or_name ( $id_or_name )
	{
	// "namespace" only is proper, but is a reserved word in current PHP versions!
		$namespace = $this->namespace_by_id ( $id_or_name );
		if ( is_null ( $namespace ) )
			$namespace = $this->namespace_by_name ( $id_or_name );
		return $namespace;
	}

	public function namespace_exists ( $id_or_name )
	{
		return $this->_namespace_exists (
			$this->namespace_by_id_or_name ( $id_or_name ) );
	}

	public function namespace_id ( $id_or_name )
	{
		return $this->_namespace_id (
			$this->namespace_by_id_or_name ( $id_or_name ) );
	}

	public function namespace_case ( $id_or_name )
	{
		return $this->_namespace_case (
			$this->namespace_by_id_or_name ( $id_or_name ) );
	}

	public function namespace_name ( $id_or_name )
	{
		return $this->namespace_basic_name ( $id_or_name );
	}

	public function namespace_basic_name ( $id_or_name )
	{
		return $this->_namespace_basic_name (
			$this->namespace_by_id_or_name ( $id_or_name ) );
	}

	public function namespace_canonical_name ( $id_or_name )
	{
		return $this->_namespace_canonical_name (
			$this->namespace_by_id_or_name ( $id_or_name ) );
	}

	public function namespace_allows_subpages ( $id_or_name )
	{
		return $this->_namespace_allows_subpages (
			$this->namespace_by_id_or_name ( $id_or_name ) );
	}

	public function namespace_allnames ( $id_or_name )
	{
		return $this->_namespace_allnames (
			$this->namespace_by_id_or_name ( $id_or_name ) );
	}


	# --- Namespace aliases --- #

	public function namespacealiases_are_present ()
	{
		return $this->site_element_isset ( 'namespacealiases' );
	}

	public function namespacealiases_count ()
	{
		return $this->site_element_subs_count ( 'namespacealiases' );
	}

	public function namespacealiases_by_id ( $id )
	{
		$aliases = array();
		$namespacealiases = $this->site_element ( "namespacealiases" );

		if ( is_array ( $namespacealiases ) )
			foreach ( $namespacealiases as $namespacealias )
				if ( $this->element_sub ( $namespacealias, 'id' ) == $id )
					$aliases[] = $this->element_sub ( $namespacealias, '*' );
		return $aliases;
	}

	public function namespace_by_alias ( $name )
	{
		$namespacealiases = $this->site_element ( "namespacealiases" );

		if ( is_array ( $namespacealiases ) )
			foreach ( $namespacealiases as $namespacealias )
				if ( $this->element_sub ( $namespacealias, '*' ) == $name )
					return $this->namespace_by_id (
						$this->element_sub ( $namespacealias, 'id' ) );
		return NULL;
	}

	public function namespacealiases_by_namespace ( $id_or_name )
	{
		return $this->namespacealiases_by_id ( $this->namespace_id ( $id_or_name ) );
	}

	public function namespacealiases ( $id_or_name = NULL )
	{
		if ( is_null ( $id_or_name ) )
			return $this->site_element ( 'namespacealiases' );
		else
			return $this->namespacealiases_by_id ( $this->namespace_id ( $id_or_name ) );
	}


	# --- Special pages --- #

	public function specialpagealiases_are_present ()
	{
		return $this->site_element_isset ( 'specialpagealiases' );
	}

	public function specialpagealiases ()
	{
		return $this->site_element ( 'specialpagealiases' );
	}

	public function specialpagealiases_count ()
	{
		return $this->site_element_subs_count ( 'specialpagealiases' );
	}

	public function specialpagealiases_names ()
	{
		return $this->site_subsubs_by_key ( 'specialpagealiases', 'realname' );
	}

	public function specialpagealiases_allnames ()
	{
		return $this->siteindex_element_arraykeys ( 'specialpagealiases_allnames' );
	}

	public function specialpagealias ( $name )
	{
		return $this->siteindex_element ( 'specialpagealiases_allnames', $name );
	}

	public function specialpagealias_realname ( $name )
	{
		$alias = $this->specialpagealias ( $name );
		return ( is_array ( $alias )
			? $alias['realname']
			: NULL );
	}

	public function specialpagealias_aliases ( $name )
	{
		$alias = $this->specialpagealias ( $name );
		return ( is_array ( $alias )
			? $alias['aliases']
			: NULL );
	}


	# --- Magicwords --- #

	public function magicwords_are_present ()
	{
		return $this->site_element_isset ( 'magicwords' );
	}

	public function magicwords ()
	{
		return $this->site_element ( 'magicwords' );
	}

	public function magicwords_count ()
	{
		return $this->site_element_subs_count ( 'magicwords' );
	}

	public function magicwords_names ()
	{
		return $this->siteindex_element_arraykeys ( 'magicwords_by_name' );
	}

	public function magicwords_aliases ()
	{
		return $this->siteindex_element_arraykeys ( 'magicwords_by_alias' );
	}

	public function magicword_by_name ( $name )
	{
		return $this->siteindex_element ( 'magicwords_by_name', $name );
	}

	public function magicword_by_alias ( $alias )
	{
		return $this->siteindex_element ( 'magicwords_by_alias', $alias );
	}

	public function magicword_by_name_or_alias ( $name_or_alias )
	{
		$magicword = $this->magicword_by_name ( $name_or_alias );
		if ( is_null ( $magicword ) )
			$magicword = $this->magicword_by_alias ( $name_or_alias );
		return $magicword;
	}

	public function magicword_allnames ( $name_or_alias )
	{
		$magicword = $this->magicword_by_name_or_alias ( $name_or_alias );
		if ( is_null ( $magicword ) )
		{
			return NULL;
		}
		else
		{
			$allnames = array_merge (
				array ( $magicword['name'] ),
				$magicword['aliases']
			);
			return $allnames;
		}
	}


	# --- Interwikis --- #

	public function interwikis_are_present ()
	{
		return $this->site_element_isset ( 'interwikimap' );
	}

	public function interwikis ()
	{
		return $this->site_element ( 'interwikimap' );
	}

	public function interwikis_count ()
	{
		return $this->site_element_subs_count ( 'interwikimap' );
	}

	public function interwikis_prefixes ()
	{
		return $this->siteindex_element_arraykeys ( 'interwikimap_by_prefix' );
	}

	public function interwikis_urls ()
	{
		return $this->siteindex_element_arraykeys ( 'interwikimap_by_url' );
	}

	public function interwikis_languages ()
	{
		return $this->siteindex_element_arraykeys ( 'interwikimap_by_language' );
	}


	public function interwiki_prefix_exists ( $prefix )
	{
		return $this->siteindex_element_isset ( 'interwikimap_by_prefix', $prefix );
	}

	public function interwiki_url_exists ( $url )
	{
		return $this->siteindex_element_isset ( 'interwikimap_by_url', $url );
	}

	public function interwiki_language_exists ( $language )
	{
		return $this->siteindex_element_isset ( 'interwikimap_by_language', $language );
	}


	public function interwiki_by_prefix ( $prefix )
	{
		return $this->siteindex_element ( 'interwikimap_by_prefix', $prefix );
	}

	public function interwiki_by_url ( $url )
	{
		return $this->siteindex_element ( 'interwikimap_by_url', $url );
	}

	public function interwikis_by_language ( $language )
	{
		return $this->siteindex_element ( 'interwikimap_by_language', $language );  // an array of interwikis!
	}


	public function interwiki_url_by_prefix ( $prefix )
	{
		$interwiki = $this->interwiki_by_prefix ( $prefix );
		return $this->element_sub ( $interwiki, 'url' );
	}

	public function interwiki_language_by_prefix ( $prefix )
	{
		$interwiki = $this->interwiki_by_prefix ( $prefix );
		return $this->element_sub ( $interwiki, 'language' );
	}

	public function interwiki_is_local_by_prefix ( $prefix )
	{
		$interwiki = $this->interwiki_by_prefix ( $prefix );
		return $this->element_sub_isset ( $interwiki, 'local' );
	}


	# --- Languages --- #

	public function languages_are_present ()
	{
		return $this->site_element_isset ( 'languages' );
	}

	public function languages ()
	{
		return $this->site_element ( 'languages' );
	}

	public function languages_count ()
	{
		return $this->site_element_subs_count ( 'languages' );
	}

	public function languages_prefixes ()
	{
		return $this->siteindex_element_arraykeys ( 'languages_by_prefix' );
	}

	public function languages_languages ()
	{
		return $this->siteindex_element_arraykeys ( 'languages_by_language' );
	}


	public function language_code_exists ( $code )
	{
		return $this->siteindex_element_isset ( 'languages_by_code', $code );
	}

	public function language_language_exists ( $language )
	{
		return $this->siteindex_element_isset ( 'languages_by_language', $language );
	}


	public function language_by_code ( $code )
	{
		return $this->siteindex_element ( 'language_by_code', $code );
	}

	public function language_by_language ( $language )
	{
		return $this->siteindex_element ( 'languages_by_language', $language );
	}


	# --- Statistics --- #

	public function statistics_are_present ()
	{
		return $this->site_element_isset ( 'statistics' );
	}

	public function statistics ()
	{
		return $this->site_element ( 'statistics' );
	}


	public function statistics_pages ()
	{
		return $this->site_sub ( 'statistics', 'pages' );
	}

	public function statistics_articles ()
	{
		return $this->site_sub ( 'statistics', 'articles' );
	}

	public function statistics_edits ()
	{
		return $this->site_sub ( 'statistics', 'edits' );
	}

	public function statistics_images ()
	{
		return $this->site_sub ( 'statistics', 'images' );
	}

	public function statistics_users ()
	{
		return $this->site_sub ( 'statistics', 'users' );
	}

	public function statistics_activeusers ()
	{
		return $this->site_sub ( 'statistics', 'activeusers' );
	}

	public function statistics_admins ()
	{
		return $this->site_sub ( 'statistics', 'admins' );
	}

	public function statistics_jobs ()
	{
		return $this->site_sub ( 'statistics', 'jobs' );
	}


	# --- Rights info --- #

	public function rightsinfo_is_present ()
	{
		return $this->site_element_isset ( 'rightsinfo' );
	}

	public function rightsinfo ()
	{
		return $this->site_element ( 'rightsinfo' );
	}


	public function rightsinfo_url ()
	{
		return $this->site_sub ( 'rightsinfo', 'url' );
	}

	public function rightsinfo_text ()
	{
		return $this->site_sub ( 'rightsinfo', 'text' );
	}


	# --- DB reply lag --- #

	public function dbrepllag_are_present ()
	{
		return $this->site_element_isset ( 'dbrepllag' );
	}

	public function dbrepllag ()
	{
		return $this->site_element ( 'dbrepllag' );
	}


	public function dbrepllag_count ()
	{
		return $this->site_element_subs_count ( 'dbrepllag' );
	}

	public function dbrepllag_hosts ()
	{
		return $this->siteindex_element_arraykeys ( 'dbrepllag_by_host' );
	}


	public function dbrepllag_host_exists ( $host )
	{
		return $this->siteindex_element_isset ( 'dbrepllag_by_host', $host );
	}


	public function dbrepllag_by_host ( $host )
	{
		return $this->siteindex_element ( 'dbrepllag_by_host', $host );
	}

	public function dbrepllag_lag_by_host ( $host )
	{
		$lag = $this->siteindex_element ( 'dbrepllag_by_host', $host );
		return $this->element_sub ( $lag, 'lag' );
	}


	# --- File extensions --- #

	public function fileextensions_are_present ()
	{
		return $this->site_element_isset ( 'fileextensions' );
	}

	public function fileextensions ()
	{
		return $this->site_element ( 'fileextensions' );
	}


	public function fileextensions_count ()
	{
		return $this->site_element_subs_count ( 'fileextensions' );
	}

	public function fileextensions_exts ()
	{
		return $this->siteindex_element_arraykeys ( 'fileextensions_by_ext' );
	}


	public function fileextension_ext_exists ( $ext )
	{
		return $this->siteindex_element_isset ( 'fileextensions_by_ext', $ext );
	}

	public function fileextension_by_ext ( $ext )
	{
		return $this->siteindex_element ( 'fileextensions_by_ext', $ext );
	}


	# --- User groups --- #

	public function usergroups_are_present ()
	{
		return $this->site_element_isset ( 'usergroups' );
	}

	public function usergroups ()
	{
		return $this->site_element ( 'usergroups' );
	}


	public function usergroups_count ()
	{
		return $this->site_element_subs_count ( 'usergroups' );
	}

	public function usergroups_names ()
	{
		return $this->siteindex_element_arraykeys ( 'usergroups_by_name' );
	}


	public function usergroup_name_exists ( $name )
	{
		return $this->siteindex_element_isset ( 'usergroups_by_name', $name );
	}

	public function usergroup_by_name ( $name )
	{
		return $this->siteindex_element ( 'usergroups_by_name', $name );
	}

	public function usergroups_by_right ( $right )
	{  // rarely needed - not worth indexing
		$usergroups = $this->usergroups();
		$groups = array();
		foreach ( $usergroups as &$usergroup )
			if ( in_array ( $right, $usergroup['rights'] ) )
				$groups[] = &$usergroup;
		return $groups;
	}


	public function usergroup_rights_by_name ( $name )
	{
		return $this->siteindex_element ( 'usergroups_by_name', 'rights' );
	}

	public function usergroup_add_by_name ( $name )
	{  // can add these rights
		return $this->siteindex_element ( 'usergroups_by_name', 'add' );
	}

	public function usergroup_remove_by_name ( $name )
	{ // can remove these rights
		return $this->siteindex_element ( 'usergroups_by_name', 'remove' );
	}

	public function usergroup_number_by_name ( $name )
	{
		return $this->siteindex_element ( 'usergroups_by_name', 'number' );
	}

	public function usergroup_has_right_by_name ( $right, $name )
	{
		$rights = $this->usergroup_rights_by_name ( $name );
		return $this->sub_in_element ( $rights, $right );
	}


	# --- Extensions --- #

	public function extensions_are_present ()
	{
		return $this->site_element_isset ( 'extensions' );
	}

	public function extensions ()
	{
		return $this->site_element ( 'extensions' );
	}


	public function extensions_count ()
	{
		return $this->site_element_subs_count ( 'extensions' );
	}

	public function extensions_names ()
	{
		return $this->siteindex_element_arraykeys ( 'extensions_by_name' );
	}

	public function extensions_descriptionmsgs ()
	{
		return $this->siteindex_element_arraykeys ( 'extensions_by_descriptionmsg' );
	}


	public function extension_name_exists ( $name )
	{
		return $this->siteindex_element_isset ( 'extensions_by_name', $name );
	}

	public function extension_by_name ( $name )
	{
		return $this->siteindex_element ( 'extensions_by_name', $name );
	}

	public function extension_descriptionmsg_exists ( $descriptionmsg )
	{
		return $this->siteindex_element_isset ( 'extensions_by_descriptionmsg', $descriptionmsg );
	}

	public function extension_by_descriptionmsg ( $descriptionmsg )
	{
		return $this->siteindex_element ( 'extensions_by_descriptionmsg', $descriptionmsg );
	}

	public function extensions_by_type ( $type )
	{
		$extensions = $this->extensions();
		$typed = array();
		foreach ( $extensions as &$extension )
			if ( $extension['type'] == $type )
				$typed[] = &$extension;
		return $typed;
	}

	public function extensions_by_description_regex ( $regex )
	{  // as of MW 1.19, very few extensions provide description!
		$extensions = $this->extensions();
		$matches = array();
		foreach ( $extensions as &$extension )
			if ( preg_match ( $regex, $extension['description'] ) )
				$matches[] = &$extension;
		return $matches;
	}

	public function extensions_by_author ( $author )
	{  // rarely needed - not worth indexing
		$extensions = $this->extensions();
		$authored = array();
		foreach ( $extensions as &$extension )
			if ( strpos ( $extension['author'], $author ) !== false )
				$authored[] = &$extension;
		return $authored;
	}


	public function extension_name_by_descriptionmsg ( $descriptionmsg )
	{
		$extension = $this->extension_by_descriptionmsg ( $descriptionmsg );
		return $this->element_sub ( $extension, 'name' );
	}

	public function extension_type_by_name ( $name )
	{
		$extension = $this->extension_by_name ( $name );
		return $this->element_sub ( $extension, 'type' );
	}

	public function extension_description_by_name ( $name )
	{
		$extension = $this->extension_by_name ( $name );
		return $this->element_sub ( $extension, 'description' );
	}

	public function extension_descriptionmsg_by_name ( $name )
	{
		$extension = $this->extension_by_name ( $name );
		return $this->element_sub ( $extension, 'descriptionmsg' );
	}

	public function extension_author_by_name ( $name )
	{
		$extension = $this->extension_by_name ( $name );
		return $this->element_sub ( $extension, 'author' );
	}

	public function extension_url_by_name ( $name )
	{
		$extension = $this->extension_by_name ( $name );
		return $this->element_sub ( $extension, 'url' );
	}


	# --- Extension tags --- #

	public function extensiontags_are_present ()
	{
		return $this->site_element_isset ( 'extensiontags' );
	}

	public function extensiontags ()
	{
		return $this->site_element ( 'extensiontags' );
	}


	public function extensiontags_count ()
	{
		return $this->site_element_subs_count ( 'extensiontags' );
	}


	public function extensiontag_exists ( $tag )
	{
		$extensiontags = $this->extensiontags();
		return $this->sub_in_element ( $extensiontags, $tag );
	}


	# --- Function hooks --- #

	public function functionhooks_are_present ()
	{
		return $this->site_element_isset ( 'functionhooks' );
	}

	public function functionhooks ()
	{
		return $this->site_element ( 'functionhooks' );
	}


	public function functionhooks_count ()
	{
		return $this->site_element_subs_count ( 'functionhooks' );
	}


	public function functionhook_exists ( $hook )
	{
		$functionhooks = $this->functionhooks();
		return $this->sub_in_element ( $functionhooks, $hook );
	}


	# --- Show hooks --- #

	public function showhooks_are_present ()
	{
		return $this->site_element_isset ( 'showhooks' );
	}

	public function showhooks ()
	{
		return $this->site_element ( 'showhooks' );
	}


	public function showhooks_count ()
	{
		return $this->site_element_subs_count ( 'showhooks' );
	}

	public function showhooks_names ()
	{
		return $this->siteindex_element_arraykeys ( 'showhooks_by_name' );
	}


	public function showhook_exists ( $hook )
	{
		return $this->siteindex_element_isset ( 'showhooks_by_name', $hook );
	}


	public function showhook_by_name ( $name )
	{
		return $this->siteindex_element ( 'showhooks_by_name', $name );
	}

	public function showhooks_by_subscriber_regex ( $regex )
	{
		$showhooks = $this->showhooks();
		$matches = array();
		foreach ( $showhooks as &$showhook )
			foreach ( $showhook['subscribers'] as $subscriber )
				if ( preg_match ( $regex, $subscriber ) )
					$matches[] = &$showhook;
		return $matches;
	}


	public function showhook_subscribers_by_name ( $name )
	{
		$showhook = $this->siteindex_element ( 'showhooks_by_name', $name );
		return $this->element_sub ( $showhook, 'subscribers' );
	}


	# --- Skins --- #

	public function skins_are_present ()
	{
		return $this->site_element_isset ( 'skins' );
	}

	public function skins ()
	{
		return $this->site_element ( 'skins' );
	}


	public function skins_count ()
	{
		return $this->site_element_subs_count ( 'skins' );
	}

	public function skins_codes ()
	{
		return $this->siteindex_element_arraykeys ( 'skins_by_code' );
	}

	public function skins_names ()
	{
		return $this->siteindex_element_arraykeys ( 'skins_by_name' );
	}


	public function skin_code_exists ( $code )
	{
		return $this->siteindex_element_isset ( 'skins_by_code', $code );
	}

	public function skin_by_code ( $code )
	{
		return $this->siteindex_element ( 'skins_by_code', $code );
	}

	public function skin_name_exists ( $name )
	{
		return $this->siteindex_element_isset ( 'skins_by_name', $name );
	}

	public function skin_by_name ( $name )
	{
		return $this->siteindex_element ( 'skins_by_name', $name );
	}

	public function skin_name_by_code ( $code )
	{
		$skin = $this->skin_by_code ( $code );
		return $this->element_sub ( $skin, '*' );
	}

	public function skin_code_by_name ( $name )
	{
		$skin = $this->skin_by_name ( $name );
		return $this->element_sub ( $skin, 'code' );
	}


# ---------------------------------------------------------------------------- #
# --                             Param info                                 -- #
# ---------------------------------------------------------------------------- #


	# --- Generic access --- #

	public function param_info ()
	{
		return $this->infotype ( "param" );
	}

	public function param_info_isset ()
	{
		return $this->infotype_isset ( 'param' );
	}


	public function param_element ( $key )
	{
		return $this->infotype_element ( 'param', $key );
	}

	public function param_element_isset ( $key )
	{
		return $this->infotype_element_isset ( 'param', $key );
	}

	public function param_element_subs_count ( $key )
	{
		return $this->infotype_element_subs_count ( 'param', $key );
	}

	public function param_element_arraykeys ( $key )
	{
		return $this->infotype_element_arraykeys ( 'param', $key );
	}


	public function param_anymodules_names ( $rootmodulename = NULL )
	{
		$param = $this->param_info();
		if ( is_null ( $rootmodulename ) )
		{
			return $this->element_arraykeys ( $param );
		}
		else
		{
			$rootmodule = $this->element_sub ( $param, $rootmodulename );
			return $this->element_arraykeys ( $rootmodule );
		}
	}

	public function param_known_anymodules_names ( $rootmodulename = NULL )
	{
		switch ( $rootmodulename )
		{
			case NULL :
				return array (
					'mainmodule',
					'pagesetmodule',
					'modules',
					'querymodules',
				);

			case "modules" :
				return array (
				);

			case "querymodules" :
				return array (
				);

			default :
				throw new ApibotException_InternalError ( "Bad parameter to Info->param_known_anymodules_names()" );
		}
	}

	public function param_unknown_anymodules_names ( $rootmodulename = NULL )
	{
		$param_modules_names = $this->param_anymodules_names ( $rootmodulename );
		return ( is_array ( $param_modules_names )
			? array_diff ( $param_modules_names,
				$this->param_known_anymodules_names ( $rootmodulename ) )
			: NULL );
	}


	public function param_anymodule ( $modulename, $rootmodulename = NULL )
	{
		if ( is_null ( $rootmodulename ) )
		{
			return $this->param_element ( $modulename );
		}
		else
		{
			$rootmodule = $this->param_element ( $rootmodulename );
			return $this->element_sub ( $rootmodule, $modulename );
		}
	}

	public function param_anymodule_exists ( $modulename, $rootmodulename = NULL )
	{
		$module = $this->param_anymodule ( $modulename, $rootmodulename );
		return is_array ( $module );
	}

	public function param_anymodule_element ( $elementname, $modulename, $rootmodulename = NULL )
	{
		$module = $this->param_anymodule ( $modulename, $rootmodulename );
		return $this->element_sub ( $module, $elementname );
	}

	public function param_anymodule_element_isset ( $elementname, $modulename, $rootmodulename = NULL )
	{
		$module = $this->param_anymodule ( $modulename, $rootmodulename );
		return $this->element_sub_isset ( $module, $elementname );
	}


	public function param_anymodule_classname ( $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_element ( 'classname', $modulename, $rootmodulename );
	}

	public function param_anymodule_description ( $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_element ( 'description', $modulename, $rootmodulename );
	}

	public function param_anymodule_examples ( $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_element ( 'examples', $modulename, $rootmodulename );
	}

	public function param_anymodule_version ( $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_element ( 'version', $modulename, $rootmodulename );
	}

	public function param_anymodule_prefix ( $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_element ( 'prefix', $modulename, $rootmodulename );
	}

	public function param_anymodule_readrights ( $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_element_isset ( 'readrights', $modulename, $rootmodulename );
	}

	public function param_anymodule_writerights ( $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_element_isset ( 'writerights', $modulename, $rootmodulename );
	}

	public function param_anymodule_mustbeposted ( $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_element_isset ( 'mustbeposted', $modulename, $rootmodulename );
	}

	public function param_anymodule_generator ( $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_element_isset ( 'generator', $modulename, $rootmodulename );
	}

	public function param_anymodule_helpurls ( $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_element ( 'helpurls', $modulename, $rootmodulename );
	}

	public function param_anymodule_allexamples ( $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_element ( 'allexamples', $modulename, $rootmodulename );
	}

	public function param_anymodule_parameters ( $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_element ( 'parameters', $modulename, $rootmodulename );
	}

	public function param_anymodule_errors ( $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_element ( 'errors', $modulename, $rootmodulename );
	}

	public function param_anymodule_querytype ( $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_element ( 'querytype', $modulename, $rootmodulename );
	}


	public function param_anymodule_parameters_names ( $modulename, $rootmodulename = NULL )
	{
		$parameters = $this->param_anymodule_parameters ( $modulename, $rootmodulename );
		return $this->element_arraykeys ( $parameters );
	}

	public function param_anymodule_parameter ( $paramname, $modulename, $rootmodulename = NULL )
	{
		$parameters = $this->param_anymodule_parameters ( $modulename, $rootmodulename );
		return $this->element_sub ( $parameters, $paramname );
	}

	public function param_anymodule_parameter_element ( $elementname, $paramname, $modulename, $rootmodulename = NULL )
	{
		$parameter = $this->param_anymodule_parameter ( $paramname, $modulename, $rootmodulename  );
		return $this->element_sub ( $parameter, $elementname );
	}

	public function param_anymodule_parameter_element_isset ( $elementname, $paramname, $modulename, $rootmodulename = NULL )
	{
		$parameter = $this->param_anymodule_parameter ( $paramname, $modulename, $rootmodulename  );
		return $this->element_sub_isset ( $parameter, $elementname );
	}

	public function param_anymodule_parameter_description ( $paramname, $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_parameter_element ( 'description', $paramname, $modulename, $rootmodulename  );
	}

	public function param_anymodule_parameter_multi ( $paramname, $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_parameter_element_isset ( 'multi', $paramname, $modulename, $rootmodulename  );
	}

	public function param_anymodule_parameter_required ( $paramname, $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_parameter_element_isset ( 'required', $paramname, $modulename, $rootmodulename  );
	}

	public function param_anymodule_parameter_allowsduplicates ( $paramname, $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_parameter_element_isset ( 'allowsduplicates', $paramname, $modulename, $rootmodulename  );
	}

	public function param_anymodule_parameter_limit ( $paramname, $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_parameter_element ( 'limit', $paramname, $modulename, $rootmodulename );
	}

	public function param_anymodule_parameter_lowlimit ( $paramname, $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_parameter_element ( 'lowlimit', $paramname, $modulename, $rootmodulename );
	}

	public function param_anymodule_parameter_highlimit ( $paramname, $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_parameter_element ( 'highlimit', $paramname, $modulename, $rootmodulename );
	}

	public function param_anymodule_parameter_default ( $paramname, $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_parameter_element ( 'default', $paramname, $modulename, $rootmodulename );
	}

	public function param_anymodule_parameter_max ( $paramname, $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_parameter_element ( 'max', $paramname, $modulename, $rootmodulename );
	}

	public function param_anymodule_parameter_highmax ( $paramname, $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_parameter_element ( 'highmax', $paramname, $modulename, $rootmodulename );
	}

	public function param_anymodule_parameter_min ( $paramname, $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_parameter_element ( 'min', $paramname, $modulename, $rootmodulename );
	}

	public function param_anymodule_parameter_type ( $paramname, $modulename, $rootmodulename = NULL )
	{
		return $this->param_anymodule_parameter_element ( 'type', $paramname, $modulename, $rootmodulename );
	}


	public function param_anymodule_errors_codes ( $modulename, $rootmodulename = NULL )
	{
		$errors = $this->param_anymodule_errors ( $modulename, $rootmodulename );
		return $this->element_arraykeys ( $errors );
	}

	public function param_anymodule_error ( $code, $modulename, $rootmodulename = NULL )
	{
		$parameters = $this->param_anymodule_errors ( $modulename, $rootmodulename );
		return $this->element_sub ( $errors, $code );
	}

	public function param_anymodule_error_info ( $code, $modulename, $rootmodulename = NULL )
	{
		$error = $this->param_anymodule_error ( $code, $modulename, $rootmodulename );
		return $this->element_sub ( $error, 'info' );
	}


	# --- Mainmodule --- #

	public function param_mainmodule ()
	{
		return $this->param_element ( 'mainmodule' );
	}

	public function param_mainmodule_exists ()
	{
		return $this->param_anymodule_exists ( 'mainmodule' );
	}


	public function param_mainmodule_classname ()
	{
		return $this->param_anymodule_classname ( 'mainmodule' );
	}

	public function param_mainmodule_description ()
	{
		return $this->param_anymodule_description ( 'mainmodule' );
	}

	public function param_mainmodule_examples ()
	{
		return $this->param_anymodule_examples ( 'mainmodule' );
	}

	public function param_mainmodule_version ()
	{
		return $this->param_anymodule_version ( 'mainmodule' );
	}

	public function param_mainmodule_prefix ()
	{
		return $this->param_anymodule_prefix ( 'mainmodule' );
	}

	public function param_mainmodule_helpurls ()
	{
		return $this->param_anymodule_helpurls ( 'mainmodule' );
	}

	public function param_mainmodule_allexamples ()
	{
		return $this->param_anymodule_allexamples ( 'mainmodule' );
	}

	public function param_mainmodule_parameters ()
	{
		return $this->param_anymodule_parameters ( 'mainmodule' );
	}

	public function param_mainmodule_errors ()
	{
		return $this->param_anymodule_errors ( 'mainmodule' );
	}


	public function param_mainmodule_parameters_names ()
	{
		return $this->param_anymodule_parameters_names ( 'mainmodule' );
	}

	public function param_mainmodule_parameter ( $name )
	{
		return $this->param_anymodule_parameter ( $name, 'mainmodule' );
	}

	public function param_mainmodule_parameter_description ( $name )
	{
		return $this->param_anymodule_parameter_description ( $name, 'mainmodule' );
	}

	public function param_mainmodule_parameter_default ( $name )
	{
		return $this->param_anymodule_parameter_default ( $name, 'mainmodule' );
	}

	public function param_mainmodule_parameter_type ( $name )
	{
		return $this->param_anymodule_parameter_type ( $name, 'mainmodule' );
	}


	public function param_mainmodule_errors_codes ()
	{
		return $this->param_anymodule_errors_codes ( 'mainmodule' );
	}

	public function param_mainmodule_error ( $code )
	{
		return $this->param_anymodule_error ( $code, 'mainmodule' );
	}

	public function param_mainmodule_error_info ( $code )
	{
		return $this->param_anymodule_error_info ( $code, 'mainmodule' );
	}


	# --- Pageset module --- #

	public function param_pagesetmodule ()
	{
		return $this->param_element ( 'pagesetmodule' );
	}

	public function param_pagesetmodule_exists ()
	{
		return $this->param_anymodule_exists ( 'pagesetmodule' );
	}


	public function param_pagesetmodule_classname ()
	{
		return $this->param_anymodule_classname ( 'pagesetmodule' );
	}

	public function param_pagesetmodule_description ()
	{
		return $this->param_anymodule_description ( 'pagesetmodule' );
	}

	public function param_pagesetmodule_examples ()
	{
		return $this->param_anymodule_examples ( 'pagesetmodule' );
	}

	public function param_pagesetmodule_version ()
	{
		return $this->param_anymodule_version ( 'pagesetmodule' );
	}

	public function param_pagesetmodule_prefix ()
	{
		return $this->param_anymodule_prefix ( 'pagesetmodule' );
	}

	public function param_pagesetmodule_readrights ()
	{
		return $this->param_anymodule_readrights ( 'pagesetmodule' );
	}

	public function param_pagesetmodule_helpurls ()
	{
		return $this->param_anymodule_helpurls ( 'pagesetmodule' );
	}

	public function param_pagesetmodule_allexamples ()
	{
		return $this->param_anymodule_allexamples ( 'pagesetmodule' );
	}

	public function param_pagesetmodule_parameters ()
	{
		return $this->param_anymodule_parameters ( 'pagesetmodule' );
	}

	public function param_pagesetmodule_errors ()
	{
		return $this->param_anymodule_errors ( 'pagesetmodule' );
	}


	public function param_pagesetmodule_parameters_names ()
	{
		return $this->param_anymodule_parameters_names ( 'pagesetmodule' );
	}

	public function param_pagesetmodule_parameter ( $name )
	{
		return $this->param_anymodule_parameter ( $name, 'pagesetmodule' );
	}

	public function param_pagesetmodule_parameter_description ( $name )
	{
		return $this->param_anymodule_parameter_description ( $name, 'pagesetmodule' );
	}

	public function param_pagesetmodule_parameter_multi ( $name )
	{
		return $this->param_anymodule_parameter_multi ( $name, 'pagesetmodule' );
	}

	public function param_pagesetmodule_parameter_limit ( $name )
	{
		return $this->param_anymodule_parameter_limit ( $name, 'pagesetmodule' );
	}

	public function param_pagesetmodule_parameter_lowlimit ( $name )
	{
		return $this->param_anymodule_parameter_lowlimit ( $name, 'pagesetmodule' );
	}

	public function param_pagesetmodule_parameter_highlimit ( $name )
	{
		return $this->param_anymodule_parameter_highlimit ( $name, 'pagesetmodule' );
	}

	public function param_pagesetmodule_parameter_type ( $name )
	{
		return $this->param_anymodule_parameter_type ( $name, 'pagesetmodule' );
	}


	public function param_pagesetmodule_errors_codes ()
	{
		return $this->param_anymodule_errors_codes ( 'pagesetmodule' );
	}

	public function param_pagesetmodule_error ( $code )
	{
		return $this->param_anymodule_error_code ( $code, 'pagesetmodule' );
	}

	public function param_pagesetmodule_error_info ( $code )
	{
		return $this->param_anymodule_error_info ( $code, 'pagesetmodule' );
	}


	# --- Standard modules --- #

	public function param_modules_names ()
	{
		return $this->param_anymodules_names ( 'modules' );
	}


	public function param_module ( $modulename )
	{
		return $this->param_anymodule ( $modulename, 'modules' );
	}

	public function param_module_exists ( $modulename )
	{
		return $this->param_anymodule_exists ( $modulename, 'modules' );
	}


	public function param_module_classname ( $modulename )
	{
		return $this->param_anymodule_classname ( $modulename, 'modules' );
	}

	public function param_module_description ( $modulename )
	{
		return $this->param_anymodule_description ( $modulename, 'modules' );
	}

	public function param_module_examples ( $modulename )
	{
		return $this->param_anymodule_examples ( $modulename, 'modules' );
	}

	public function param_module_version ( $modulename )
	{
		return $this->param_anymodule_version ( $modulename, 'modules' );
	}

	public function param_module_prefix ( $modulename )
	{
		return $this->param_anymodule_prefix ( $modulename, 'modules' );
	}

	public function param_module_readrights ( $modulename )
	{
		return $this->param_anymodule_readrights ( $modulename, 'modules' );
	}

	public function param_module_writerights ( $modulename )
	{
		return $this->param_anymodule_writerights ( $modulename, 'modules' );
	}

	public function param_module_mustbeposted ( $modulename )
	{
		return $this->param_anymodule_mustbeposted ( $modulename, 'modules' );
	}

	public function param_module_helpurls ( $modulename )
	{
		return $this->param_anymodule_helpurls ( $modulename, 'modules' );
	}

	public function param_module_allexamples ( $modulename )
	{
		return $this->param_anymodule_allexamples ( $modulename, 'modules' );
	}

	public function param_module_parameters ( $modulename )
	{
		return $this->param_anymodule_parameters ( $modulename, 'modules' );
	}

	public function param_module_errors ( $modulename )
	{
		return $this->param_anymodule_errors ( $modulename, 'modules' );
	}


	public function param_module_parameters_names ( $modulename )
	{
		return $this->param_anymodule_parameters_names ( $modulename, 'modules' );
	}

	public function param_module_parameter ( $name, $modulename )
	{
		return $this->param_anymodule_parameter ( $name, $modulename, 'modules' );
	}

	public function param_module_parameter_description ( $name, $modulename )
	{
		return $this->param_anymodule_parameter_description ( $name, $modulename, 'modules' );
	}

	public function param_module_parameter_multi ( $name, $modulename )
	{
		return $this->param_anymodule_parameter_multi ( $name, $modulename, 'modules' );
	}

	public function param_module_parameter_required ( $name, $modulename )
	{
		return $this->param_anymodule_parameter_required ( $name, $modulename, 'modules' );
	}

	public function param_module_parameter_allowsduplicates ( $name, $modulename )
	{
		return $this->param_anymodule_parameter_allowsduplicates ( $name, $modulename, 'modules' );
	}

	public function param_module_parameter_limit ( $name, $modulename )
	{
		return $this->param_anymodule_parameter_limit ( $name, $modulename, 'modules' );
	}

	public function param_module_parameter_lowlimit ( $name, $modulename )
	{
		return $this->param_anymodule_parameter_lowlimit ( $name, $modulename, 'modules' );
	}

	public function param_module_parameter_highlimit ( $name, $modulename, $modulename )
	{
		return $this->param_anymodule_parameter_highlimit ( $name, $modulename, 'modules' );
	}

	public function param_module_parameter_default ( $name, $modulename, $modulename )
	{
		return $this->param_anymodule_parameter_default ( $name, $modulename, 'modules' );
	}

	public function param_module_parameter_max ( $name, $modulename )
	{
		return $this->param_anymodule_parameter_max ( $name, $modulename, 'modules' );
	}

	public function param_module_parameter_highmax ( $name, $modulename, $modulename )
	{
		return $this->param_anymodule_parameter_highmax ( $name, $modulename, 'modules' );
	}

	public function param_module_parameter_min ( $name, $modulename )
	{
		return $this->param_anymodule_parameter_min ( $name, $modulename, 'modules' );
	}

	public function param_module_parameter_type ( $name, $modulename )
	{
		return $this->param_anymodule_parameter_type ( $name, $modulename, 'modules' );
	}


	public function param_module_errors_codes ( $modulename )
	{
		return $this->param_anymodule_errors_codes ( $modulename, 'modules' );
	}

	public function param_module_error ( $code, $modulename )
	{
		return $this->param_anymodule_error_code ( $code, $modulename, 'modules' );
	}

	public function param_module_error_info ( $code, $modulename )
	{
		return $this->param_anymodule_error_info ( $code, $modulename, 'modules' );
	}


	# --- Query modules --- #

	public function param_querymodules_names ()
	{
		return $this->param_anymodules_names ( 'querymodules' );
	}


	public function param_querymodule ( $modulename )
	{
		return $this->param_anymodule ( $modulename, 'querymodules' );
	}

	public function param_querymodule_exists ( $modulename )
	{
		return $this->param_anymodule_exists ( $modulename, 'querymodules' );
	}


	public function param_querymodule_classname ( $modulename )
	{
		return $this->param_anymodule_classname ( $modulename, 'querymodules' );
	}

	public function param_querymodule_description ( $modulename )
	{
		return $this->param_anymodule_description ( $modulename, 'querymodules' );
	}

	public function param_querymodule_examples ( $modulename )
	{
		return $this->param_anymodule_examples ( $modulename, 'querymodules' );
	}

	public function param_querymodule_version ( $modulename )
	{
		return $this->param_anymodule_version ( $modulename, 'querymodules' );
	}

	public function param_querymodule_prefix ( $modulename )
	{
		return $this->param_anymodule_prefix ( $modulename, 'querymodules' );
	}

	public function param_querymodule_readrights ( $modulename )
	{
		return $this->param_anymodule_readrights ( $modulename, 'querymodules' );
	}

	public function param_querymodule_writerights ( $modulename )
	{
		return $this->param_anymodule_writerights ( $modulename, 'querymodules' );
	}

	public function param_querymodule_mustbeposted ( $modulename )
	{
		return $this->param_anymodule_mustbeposted ( $modulename, 'querymodules' );
	}

	public function param_querymodule_helpurls ( $modulename )
	{
		return $this->param_anymodule_helpurls ( $modulename, 'querymodules' );
	}

	public function param_querymodule_allexamples ( $modulename )
	{
		return $this->param_anymodule_allexamples ( $modulename, 'querymodules' );
	}

	public function param_querymodule_parameters ( $modulename )
	{
		return $this->param_anymodule_parameters ( $modulename, 'querymodules' );
	}

	public function param_querymodule_errors ( $modulename )
	{
		return $this->param_anymodule_errors ( $modulename, 'querymodules' );
	}


	public function param_querymodule_parameters_names ( $modulename )
	{
		return $this->param_anymodule_parameters_names ( $modulename, 'querymodules' );
	}

	public function param_querymodule_parameter ( $name, $modulename )
	{
		return $this->param_anymodule_parameter ( $name, $modulename, 'querymodules' );
	}

	public function param_querymodule_parameter_description ( $name, $modulename )
	{
		return $this->param_anymodule_parameter_description ( $name, $modulename, 'querymodules' );
	}

	public function param_querymodule_parameter_multi ( $name, $modulename )
	{
		return $this->param_anymodule_parameter_multi ( $name, $modulename, 'querymodules' );
	}

	public function param_querymodule_parameter_required ( $name, $modulename )
	{
		return $this->param_anymodule_parameter_required ( $name, $modulename, 'querymodules' );
	}

	public function param_querymodule_parameter_allowsduplicates ( $name, $modulename )
	{
		return $this->param_anymodule_parameter_allowsduplicates ( $name, $modulename, 'querymodules' );
	}

	public function param_querymodule_parameter_limit ( $name, $modulename )
	{
		return $this->param_anymodule_parameter_limit ( $name, $modulename, 'querymodules' );
	}

	public function param_querymodule_parameter_lowlimit ( $name, $modulename )
	{
		return $this->param_anymodule_parameter_lowlimit ( $name, $modulename, 'querymodules' );
	}

	public function param_querymodule_parameter_highlimit ( $name, $modulename )
	{
		return $this->param_anymodule_parameter_highlimit ( $name, $modulename, 'querymodules' );
	}

	public function param_querymodule_parameter_default ( $paramname, $modulename )
	{
		return $this->param_anymodule_parameter_default ( $paramname, $modulename, 'querymodules' );
	}

	public function param_querymodule_parameter_max ( $paramname, $modulename )
	{
		return $this->param_anymodule_parameter_max ( $paramname, $modulename, 'querymodules' );
	}

	public function param_querymodule_parameter_highmax ( $paramname, $modulename )
	{
		return $this->param_anymodule_parameter_highmax ( $paramname, $modulename, 'querymodules' );
	}

	public function param_querymodule_parameter_min ( $paramname, $modulename )
	{
		return $this->param_anymodule_parameter_min ( $paramname, $modulename, 'querymodules' );
	}

	public function param_querymodule_parameter_type ( $name, $modulename )
	{
		return $this->param_anymodule_parameter_type ( $name, $modulename, 'querymodules' );
	}


	public function param_querymodule_errors_codes ( $modulename )
	{
		return $this->param_anymodule_errors_codes ( $modulename, 'querymodules' );
	}

	public function param_querymodule_error ( $code, $modulename )
	{
		return $this->param_anymodule_error_code ( $code, $modulename, 'querymodules' );
	}

	public function param_querymodule_error_info ( $code, $modulename )
	{
		return $this->param_anymodule_error_info ( $code, $modulename, 'querymodules' );
	}


# ---------------------------------------------------------------------------- #
# --                              User info                                 -- #
# ---------------------------------------------------------------------------- #


	# --- Generic access --- #

	public function user_info ()
	{
		return $this->infotype ( "user" );
	}

	public function user_info_isset ()
	{
		return $this->infotype_isset ( 'user' );
	}


	public function user_element ( $key )
	{
		return $this->infotype_element ( 'user', $key );
	}

	public function user_element_isset ( $key )
	{
		return $this->infotype_element_isset ( 'user', $key );
	}

	public function user_element_subs_count ( $key )
	{
		return $this->infotype_element_subs_count ( 'user', $key );
	}

	public function user_element_arraykeys ( $key )
	{
		return $this->infotype_element_arraykeys ( 'user', $key );
	}

	public function user_sub ( $key, $subkey )
	{
		return $this->infotype_sub ( 'user', $key, $subkey );
	}

	public function user_sub_isset ( $key, $subkey )
	{
		$element = $this->infotype_element ( 'user', $key );
		return $this->element_sub_isset ( $element, $key );
	}

	public function user_sub_in_element ( $key, $subkey )
	{
		$element = $this->infotype_element ( 'user', $key );
		return $this->sub_in_element ( $element, $subkey );
	}


	public function user_id ()
	{
		return $this->user_element ( 'id' );
	}

	public function user_name ()
	{
		return $this->user_element ( 'name' );
	}

	public function user_groups ()
	{
		return $this->user_element ( 'groups' );
	}

	public function user_implicitgroups ()
	{
		return $this->user_element ( 'implicitgroups' );
	}

	public function user_rights ()
	{
		return $this->user_element ( 'rights' );
	}

	public function user_changeablegroups ()
	{
		return $this->user_element ( 'changeablegroups' );
	}

	public function user_options ()
	{
		return $this->user_element ( 'options' );
	}

	public function user_preferencestoken ()
	{
		return $this->user_element ( 'preferencestoken' );
	}

	public function user_editcount ()
	{
		return $this->user_element ( 'editcount' );
	}

	public function user_ratelimits ()
	{
		return $this->user_element ( 'ratelimits' );
	}

	public function user_email ()
	{
		return $this->user_element ( 'email' );
	}

	public function user_registrationdate ()
	{
		return $this->user_element ( 'registrationdate' );
	}

	public function user_acceptlang ()
	{
		return $this->user_element ( 'acceptlang' );
	}


	public function user_group_in_groups ( $group )
	{
		$groups = $this->user_groups();
		return $this->sub_in_element ( $groups, $group );
	}

	public function user_group_in_implicitgroups ( $group )
	{
		$groups = $this->user_implicitgroups();
		return $this->sub_in_element ( $groups, $group );
	}

	public function user_right_in_rights ( $right )
	{
		$rights = $this->user_rights();
		return $this->sub_in_element ( $rights, $right );
	}


	public function user_changeablegroups_add ()
	{
		return $this->user_sub ( 'changeablegroups', 'add' );
	}

	public function user_changeablegroups_remove ()
	{
		return $this->user_sub ( 'changeablegroups', 'remove' );
	}

	public function user_changeablegroups_add_self ()
	{
		return $this->user_sub ( 'changeablegroups', 'add-self' );
	}

	public function user_changeablegroups_remove_self ()
	{
		return $this->user_sub ( 'changeablegroups', 'remove-self' );
	}


	public function user_group_in_changeablegroups_add ( $group )
	{
		$groups = $this->user_sub ( 'changeablegroups', 'add' );
		return $this->sub_in_element ( $groups, $group );
	}

	public function user_group_in_changeablegroups_remove ()
	{
		$groups = $this->user_sub ( 'changeablegroups', 'remove' );
		return $this->sub_in_element ( $groups, $group );
	}

	public function user_group_in_changeablegroups_add_self ()
	{
		$groups = $this->user_sub ( 'changeablegroups', 'add-self' );
		return $this->sub_in_element ( $groups, $group );
	}

	public function user_group_in_changeablegroups_remove_self ()
	{
		$groups = $this->user_sub ( 'changeablegroups', 'remove-self' );
		return $this->sub_in_element ( $groups, $group );
	}


	public function user_option ( $name )
	{
		return $this->user_sub ( 'options', $name );
	}


	public function user_ratelimits_by_action ( $action )
	{
		return $this->user_sub ( 'ratelimits', $action );
	}

	public function user_ratelimits_by_action_group ( $action, $group )
	{
		$action = $this->user_sub ( 'ratelimits', $action );
		return $this->element_sub ( $action, $group );
	}

	public function user_ratelimits_hits_by_action_group ( $action, $group )
	{
		$group = $this->user_ratelimits_by_action_group ( $action, $group );
		return $this->element_sub ( $group, 'hits' );
	}

	public function user_ratelimits_seconds_by_action_group ( $action, $group )
	{
		$group = $this->user_ratelimits_by_action_group ( $action, $group );
		return $this->element_sub ( $group, 'seconds' );
	}


	public function user_acceptlang_count ()
	{
		return $this->user_element_subs_count ( 'acceptlang' );
	}

	public function user_acceptlang_names ()
	{
		return $this->user_element_arraykeys ( 'acceptlang' );
	}

	public function user_acceptlang_by_name ( $name )
	{
		return $this->user_sub ( 'acceptlang', $name );
	}


# ---------------------------------------------------------------------------- #
# --                           Allmessages info                             -- #
# ---------------------------------------------------------------------------- #


	# --- Generic access --- #

	public function allmessages_info ()
	{
		return $this->infotype ( "allmessages" );
	}

	public function allmessages_info_isset ()
	{
		return $this->infotype_isset ( 'allmessages' );
	}


	public function allmessages_count ()
	{
		$info = $this->allmessages_info();
		return $this->element_subs_count ( $info );
	}

	public function allmessages_names ()
	{
		$info = $this->allmessages_info();
		return $this->element_arraykeys ( $info );
	}


	public function allmessage_by_name ( $name )
	{
		$info = $this->allmessages_info();
		return $this->element_sub ( $info, $name );
	}


	# ----- Filerepo info ----- #

	# --- Generic access --- #

	public function filerepo_info ()
	{
		return $this->infotype ( "filerepo" );
	}

	public function filerepo_info_isset ()
	{
		return $this->infotype_isset ( 'filerepo' );
	}


	public function filerepo_count ()
	{
		$info = $this->filerepo_info();
		return $this->element_subs_count ( $info );
	}

	public function filerepo_keys ()
	{
		$info = $this->filerepo_info();
		return $this->element_arraykeys ( $info );
	}


	public function filerepo_by_key ( $key )
	{
		$info = $this->filerepo_info();
		return $this->element_sub ( $info, $key );
	}


# ---------------------------------------------------------------------------- #
# --                           Global user info                             -- #
# ---------------------------------------------------------------------------- #


	# --- Generic access --- #

	public function globaluser_info ()
	{
		return $this->infotype ( "globaluser" );
	}

	public function globaluser_info_isset ()
	{
		return $this->infotype_isset ( 'globaluser' );
	}


	public function globaluser_element ( $element )
	{
		return $this->infotype_element ( 'globaluser', $element );
	}

	public function globaluser_home ()
	{
		return $this->globaluser_element ( 'home' );
	}

	public function globaluser_id ()
	{
		return $this->globaluser_element ( 'id' );
	}

	public function globaluser_registration ()
	{
		return $this->globaluser_element ( 'registration' );
	}

	public function globaluser_groups ()
	{
		return $this->globaluser_element ( 'groups' );
	}

	public function globaluser_rights ()
	{
		return $this->globaluser_element ( 'rights' );
	}

	public function globaluser_merged ()
	{
		return $this->globaluser_element ( 'merged' );
	}

	public function globaluser_unattached ()
	{
		return $this->globaluser_element ( 'unattached' );
	}


	public function user_group_in_globaluser_groups ( $group )
	{
		$groups = $this->globaluser_groups();
		return $this->sub_in_element ( $groups, $group );
	}

	public function user_right_in_globaluser_rights ( $right )
	{
		$rights = $this->globaluser_rights();
		return $this->sub_in_element ( $rights, $right );
	}


	public function globaluser_merged_wiki ( $wikiid )
	{
		$wikis = $this->globaluser_merged();
		return $this->element_sub ( $wikis, $wikiid );
	}

	public function globaluser_merged_wiki_exists ( $wikiid )
	{
		$wikis = $this->globaluser_merged();
		return $this->element_sub_exists ( $wikis, $wikiid );
	}

	public function globaluser_merged_wiki_url ( $wikiid )
	{
		$wiki = $this->globaluser_merged_wiki ( $wikiid );
		return $this->element_sub ( $wiki, 'url' );
	}

	public function globaluser_merged_wiki_timestamp ( $wikiid )
	{
		$wiki = $this->globaluser_merged_wiki ( $wikiid );
		return $this->element_sub ( $wiki, 'timestamp' );
	}

	public function globaluser_merged_wiki_method ( $wikiid )
	{
		$wiki = $this->globaluser_merged_wiki ( $wikiid );
		return $this->element_sub ( $wiki, 'method' );
	}

	public function globaluser_merged_wiki_editcount ( $wikiid )
	{
		$wiki = $this->globaluser_merged_wiki ( $wikiid );
		return $this->element_sub ( $wiki, 'editcount' );
	}


# ---------------------------------------------------------------------------- #
# --                                                                        -- #
# --                        Info specific access                            -- #
# --                                                                        -- #
# ---------------------------------------------------------------------------- #


# ---------------------------------------------------------------------------- #
# --                        Elements availability                           -- #
# ---------------------------------------------------------------------------- #


	public function available_xfer_formats ()
	{
		return $this->param_mainmodule_parameter_type ( 'format' );
	}

	public function is_available_xfer_format ( $format )
	{
		return $this->sub_in_element ( $this->available_xfer_formats(), $format );
	}


	public function available_actions ()
	{
		return $this->param_mainmodule_parameter_type ( 'action' );
	}

	public function is_available_action ( $action )
	{
		return $this->sub_in_element ( $this->available_actions(), $action );
	}


	public function available_properties ()
	{
		return $this->param_anymodule_parameter_type ( 'prop', 'query', 'modules' );
	}

	public function is_available_property ( $property )
	{
		return $this->sub_in_element ( $this->available_properties(), $property );
	}


	public function available_lists ()
	{
		return $this->param_anymodule_parameter_type ( 'list', 'query', 'modules' );
	}

	public function is_available_list ( $list )
	{
		return $this->sub_in_element ( $this->available_lists(), $list );
	}


	public function available_generators ()
	{
		return $this->param_anymodule_parameter_type ( 'generator', 'query', 'modules' );
	}

	public function is_available_generator ( $generator )
	{
		return $this->sub_in_element ( $this->available_generators(), $generator );
	}


	public function available_meta ()
	{
		return $this->param_anymodule_parameter_type ( 'meta', 'query', 'modules' );
	}

	public function is_available_meta ( $meta )
	{
		return $this->sub_in_element ( $this->available_meta(), $meta );
	}


	public function is_available_siteinfo ( $siteinfo_type )
	{
		return $this->sub_in_element ( $this->site_elements_names(), $siteinfo_type );
	}


	# --- Module limit, for the current user --- #

	public function anymodule_available_limit ( $modulename, $rootmodulename )
	{
		if ( $this->user_right_in_rights ( 'apihighlimits' ) )
			return $this->param_anymodule_parameter_highmax ( 'limit',
				$modulename, $rootmodulename );
		else
			return $this->param_anymodule_parameter_max ( 'limit',
				$modulename, $rootmodulename );
	}

	public function module_available_limit ( $modulename )
	{
		return $this->anymodule_available_limit ( $modulename, 'modules' );
	}

	public function querymodule_available_limit ( $modulename )
	{
		return $this->anymodule_available_limit ( $modulename, 'querymodules' );
	}

	public function pagesetmodule_available_limit ( $with_content = false )
	{
		if ( $this->user_right_in_rights ( 'apihighlimits' ) )
			$limit = $this->param_pagesetmodule_parameter_highlimit ( 'titles' );
		else
			$limit = $this->param_pagesetmodule_parameter_limit ( 'titles' );

		if ( $with_content )
			$limit = $limit / 10;

		return $limit;
	}


	# --- Module parameters / values checking --- #

	public function mainmodule_paramnames ()
	{
		return $this->param_anymodule_parameters_names ( "mainmodule" );
	}

	public function pagesetmodule_paramnames ()
	{
		return $this->param_anymodule_parameters_names ( "pagesetmodule" );
	}

	public function module_paramnames ( $modulename )
	{
		return $this->param_anymodule_parameters_names ( $modulename,
			"modules" );
	}

	public function querymodule_paramnames ( $modulename )
	{
		return $this->param_anymodule_parameters_names ( $modulename,
			"querymodules" );
	}


	protected function param_is_ok ( $paramname, $modulename, $rootmodulename )
	{
		$paramnames = $this->param_anymodule_parameters_names (
			$modulename, $rootmodulename );
		if ( is_array ( $paramnames ) )
			return in_array ( $paramname, $paramnames );
		return NULL;
	}

	public function module_param_is_ok ( $paramname, $modulename )
	{
		return $this->param_is_ok ( $paramname, $modulename, 'modules' );
	}

	public function querymodule_param_is_ok ( $paramname, $modulename )
	{
		return $this->param_is_ok ( $paramname, $modulename, 'querymodules' );
	}


	# ----- Specific parameters ----- #

	public function universal_query_paramnames ()  // todo! unification for function names in this section!
	{
		$paramnames = $this->module_paramnames ( "query" );
		$nonuniversal_paramnames = array (  // maybe return them from some function(s)?
			'prop', 'list', 'generator', 'meta',
			'titles', 'pageids', 'revids',
			'continue',
		);
		return array_diff ( $paramnames, $nonuniversal_paramnames );
	}


# ---------------------------------------------------------------------------- #
# --                          Specific user data                            -- #
# ---------------------------------------------------------------------------- #


	# --- Common --- #

	public function am_i_anonymous ()
	{
		return $this->infotype_element_isset ( 'user', "anon" );
	}


	# --- Groups --- #

	public function am_i_member_of_sysops ()
	{
		return $this->user_group_in_groups ( 'sysop' );
	}

	public function am_i_member_of_bureaucrats ()
	{
		return $this->user_group_in_groups ( 'bureaucrat' );
	}

	public function am_i_member_of_bots ()
	{
		return $this->user_group_in_groups ( 'bot' );
	}


	# --- Permissions --- #

	public function permission_min_wv ( $permission )
	{
		switch ( $permission )
		{
			case 'bot'                  : return 10500;
			case 'autoconfirmed'        : return 10600;
			case 'emailconfirmed'       : return 10700;
			case 'ipblock-exempt'       : return 10900;
			case 'proxyunbannable'      : return 10500;
			case 'apihighlimits'        : return 11200;
			case 'noratelimit'          : return 11300;
			case 'read'                 : return 10500;
			case 'edit'                 : return 10500;
			case 'editprotected'        : return 11300;
			case 'minoredit'            : return 10600;
			case 'skipcaptcha'          : return 11700;
			case 'createpage'           : return 10600;
			case 'createtalk'           : return 10600;
			case 'nominornewtalk'       : return 10900;
			case 'writeapi'             : return 11300;
			case 'rollback'             : return 10500;
			case 'markbotedits'         : return 11200;
			case 'import'               : return 10500;
			case 'importupload'         : return 10500;
			case 'move'                 : return 10500;
			case 'movefile'             : return 11400;
			case 'move-subpages'        : return 11300;
			case 'move-rootuserpages'   : return 11400;
			case 'suppressredirect'     : return 11200;
			case 'upload'               : return 10500;
			case 'reupload'             : return 10600;
			case 'reupload-own'         : return 11100;
			case 'reupload-shared'      : return 10600;
			case 'upload_by_url'        : return 10800;
			case 'deletedhistory'       : return 10600;
			case 'delete'               : return 10500;
			case 'bigdelete'            : return 11200;
			case 'purge'                : return 11000;
			case 'undelete'             : return 11200;
			case 'browsearchive'        : return 11300;
			case 'mergehistory'         : return 11200;
			case 'suppressrevision'     : return 10600;
			case 'deleterevision'       : return 10600;
			case 'protect'              : return 10500;
			case 'patrol'               : return 10500;
			case 'autopatrol'           : return 10900;
			case 'hideuser'             : return 11000;
			case 'block'                : return 10500;
			case 'blockemail'           : return 11100;
			case 'createaccount'        : return 10500;
			case 'userrights'           : return 10500;
			case 'userrights-interwiki' : return 11200;
			case 'editinterface'        : return 10500;
			case 'editusercssjs'        : return 11200;
			case 'sendemail'            : return 11700;
			case 'trackback'            : return 10700;
			case 'unwatchedpages'       : return 10600;
			default: return NULL;
		}
	}

	public function permission_max_wv ( $permission )
	{
		switch ( $permission )
		{
			case 'emailconfirmed' : return 11300;
			default : return NULL;
		}
	}

	public function have_i_permission ( $permission )
	{
		$current_wv = $this->wiki_version_number();

		if ( ( $permission == "undelete" ) && ( $current_wv < 11200 ) )
			$permission = "delete";

		$min_wv = $this->permission_min_wv ( $permission );
		$max_wv = $this->permission_max_wv ( $permission );
		if ( is_null ( $max_wv ) )
			$max_wv = PHP_INT_MAX;

		if ( ( $current_wv >= $min_wv ) && ( $current_wv <= $max_wv ) )
			return $this->user_right_in_rights ( $permission );
		else
			return NULL;
	}


	public function am_i_bot ()
	{
		return $this->have_i_permission ( 'bot' );
	}

	public function am_i_autoconfirmed ()
	{
		return $this->have_i_permission ( 'autoconfirmed' );
	}

	public function am_i_emailconfirmed ()
	{
		return $this->have_i_permission ( 'emailconfirmed' );
	}


	public function am_i_ipblock_exempt ()
	{
		return $this->have_i_permission ( 'ipblock-exempt' );
	}

	public function am_i_proxyunbannable ()
	{
		return $this->have_i_permission ( 'proxyunbannable' );
	}


	public function have_i_highlimits ()
	{
		return $this->have_i_permission ( 'apihighlimits' );
	}

	public function have_i_noratelimit ()
	{
		return $this->have_i_permission ( 'noratelimit' );
	}


	public function can_i_read ()
	{
		return $this->have_i_permission ( 'read' );
	}

	public function can_i_edit ()
	{
		return $this->have_i_permission ( 'edit' );
	}

	public function can_i_editprotected ()
	{
		return $this->have_i_permission ( 'editprotected' );
	}

	public function can_i_minoredit ()
	{
		return $this->have_i_permission ( 'minoredit' );
	}

	public function can_i_skipcaptcha ()
	{  // could not find it described??
		return $this->have_i_permission ( 'skipcaptcha' );
	}

	public function can_i_createpage ()
	{
		return $this->have_i_permission ( 'createpage' );
	}

	public function can_i_createtalk ()
	{
		return $this->have_i_permission ( 'createtalk' );
	}

	public function can_i_nominornewtalk ()
	{
		return $this->have_i_permission ( 'nominornewtalk' );
	}

	public function can_i_writeapi ()
	{  // user-specific, unlike api_write_enabled()
		return $this->have_i_permission ( 'writeapi' );
	}


	public function can_i_rollback ()
	{
		return $this->have_i_permission ( 'rollback' );
	}

	public function can_i_markbotedits ()
	{
		return $this->have_i_permission ( 'markbotedits' );
	}


	public function can_i_import ()
	{
		return $this->have_i_permission ( 'import' );
	}

	public function can_i_importupload ()
	{
		return $this->have_i_permission ( 'importupload' );
	}


	public function can_i_move ()
	{
		return $this->have_i_permission ( 'move' );
	}

	public function can_i_movefile ()
	{
		return $this->have_i_permission ( 'movefile' );
	}

	public function can_i_move_subpages ()
	{
		return $this->have_i_permission ( 'move-subpages' );
	}

	public function can_i_move_rootuserpages ()
	{
		return $this->have_i_permission ( 'move-rootuserpages' );
	}

	public function can_i_suppressredirect ()
	{
		return $this->have_i_permission ( 'suppressredirect' );
	}


	public function can_i_upload ()
	{
		return $this->have_i_permission ( 'upload' );
	}

	public function can_i_reupload ()
	{
		return $this->have_i_permission ( 'reupload' );
	}

	public function can_i_reupload_own ()
	{
		return $this->have_i_permission ( 'reupload-own' );
	}

	public function can_i_reupload_shared ()
	{
		return $this->have_i_permission ( 'reupload-shared' );
	}

	public function can_i_upload_by_url ()
	{
		return $this->have_i_permission ( 'upload_by_url' );
	}


	public function can_i_see_deletedhistory ()
	{
		return $this->have_i_permission ( 'deletedhistory' );
	}

	public function can_i_delete ()
	{
		return $this->have_i_permission ( 'delete' );
	}

	public function can_i_bigdelete ()
	{
		return $this->have_i_permission ( 'bigdelete' );
	}

	public function can_i_purge ()
	{
		return $this->have_i_permission ( 'purge' );
	}

	public function can_i_undelete ()
	{
		return $this->have_i_permission ( 'undelete' );
	}


	public function can_i_browsearchive ()
	{
		return $this->have_i_permission ( 'browsearchive' );
	}

	public function can_i_mergehistory ()
	{
		return $this->have_i_permission ( 'mergehistory' );
	}

	public function can_i_suppressrevision ()
	{
		return $this->have_i_permission ( 'suppressrevision' );
	}

	public function can_i_deleterevision ()
	{
		return $this->have_i_permission ( 'deleterevision' );
	}


	public function can_i_protect ()
	{
		return $this->have_i_permission ( 'protect' );
	}


	public function can_i_patrol ()
	{
		return $this->have_i_permission ( 'patrol' );
	}

	public function can_i_autopatrol ()
	{
		return $this->have_i_permission ( 'autopatrol' );
	}

	public function can_i_hideuser ()
	{
		return $this->have_i_permission ( 'hideuser' );
	}


	public function can_i_block ()
	{
		return $this->have_i_permission ( 'block' );
	}

	public function can_i_blockemail ()
	{
		return $this->have_i_permission ( 'blockemail' );
	}


	public function can_i_createaccount ()
	{
		return $this->have_i_permission ( 'createaccount' );
	}

	public function can_i_userrights ()
	{
		return $this->have_i_permission ( 'userrights' );
	}

	public function can_i_userrights_interwiki ()
	{
		return $this->have_i_permission ( 'userrights-interwiki' );
	}

	public function can_i_editinterface ()
	{
		return $this->have_i_permission ( 'editinterface' );
	}

	public function can_i_editusercssjs ()
	{
		return $this->have_i_permission ( 'editusercssjs' );
	}

	public function can_i_sendemail ()
	{  // could not find it described??
		return $this->have_i_permission ( 'sendemail' );
	}


	public function can_i_remove_trackbacks ()
	{
		return $this->have_i_permission ( 'trackback' );
	}

	public function can_i_see_unwatchedpages ()
	{
		return $this->have_i_permission ( 'unwatchedpages' );
	}


# ---------------------------------------------------------------------------- #
# --                     Namespaces specific support                        -- #
# ---------------------------------------------------------------------------- #


	public function is_main_namespace_type ( $id_or_name )
	{
		$namespace = $this->namespace_by_id_or_name ( $id_or_name );
		return ( ( $namespace['id'] >= 0 ) && ( ( $namespace['id'] % 2 ) == 0 ) );
	}

	public function is_talk_namespace_type ( $id_or_name )
	{
		$namespace = $this->namespace_by_id_or_name ( $id_or_name );
		return ( ( $namespace['id'] >= 0 ) && ( ( $namespace['id'] % 2 ) == 1 ) );
	}

	public function is_special_namespace_type ( $id_or_name )
	{
		$namespace = $this->namespace_by_id_or_name ( $id_or_name );
		return ( $namespace['id'] < 0 );
	}


	public function namespace_names_and_aliases ( $id_or_name )
	{
		$namespace = $this->namespace_by_alias ( $id_or_name );
		if ( is_null ( $namespace ) )
			$namespace = $this->namespace_by_id_or_name ( $id_or_name );

		$names = $this->namespace_allnames_by_id ( $namespace['id'] );
		$aliases = $this->namespacealiases_by_id ( $namespace['id'] );

		return array_unique ( array_merge ( $names, $aliases ) );
	}


	public function given_namespaces_ids ( $namespaces_ids_or_names )
	{
		if ( is_array ( $namespaces_ids_or_names ) )
		{
			$ids = array();
			foreach ( $namespaces_ids_or_names as $namespace )
			{
				if ( ! is_numeric ( $namespace ) )
				{
					$namespace = $this->namespace_id ( $namespace );
					if ( is_null ( $namespace ) )
					{
						$this->log ( "Error: Could not validate namespace '" .
						$namespace . "'!", LL_ERROR );
						$this->log ( "  (bad spelling? missing siteinfo?...)", LL_DEBUG );
					}
				}
				$ids[] = $namespace;
			}

			return $ids;
		}
		else
		{
			$namespaces_ids_or_names = array ( $namespaces_ids_or_names );
			$ids = $this->given_namespaces_ids ( $namespaces_ids_or_names );
			return reset ( $ids );
		}
	}


	# ----- Page titles support ----- #

	public function title_parts ( $title, $partname = NULL )
	{
		$parts = array();
		$pieces = explode ( ':', $title );

		$parts['wiki'] = "";
		$parts['namespace'] = "";
		$parts['name'] = end ( $pieces );
		if ( count ( $pieces ) > 1 )
		{
			$parts['namespace'] = prev ( $pieces );
			if ( ! is_int ( $this->namespace_id ( $parts['namespace'] ) ) )
			{
				$parts['wiki'] = $parts['namespace'];
				$parts['namespace'] = "";
			}
		}

		if ( count ( $pieces ) > 2 )
			$parts['wiki'] = prev ( $pieces );

		if ( empty ( $partname ) )
			return $parts;
		else
			return $parts[$partname];
	}

	public function parts_title ( $parts )
	{
		if ( is_string ( $parts ) )
			return $parts;

		$title = "";
		if ( ! empty ( $parts['wiki'] ) )
			$title .= $parts['wiki'] . ':';
		if ( ! empty ( $parts['namespace'] ) )
			$title .= $parts['namespace'] . ':';
		$title .= $parts['name'];
		return $title;
	}


	public function title_interwiki ( $title )
	{
		return $this->title_parts ( $title, 'wiki' );
	}

	public function title_namespace ( $title )
	{
		return $this->title_parts ( $title, 'namespace' );
	}

	public function title_name ( $title )
	{
		return $this->title_parts ( $title, 'name' );
	}


	public function title_namespace_id ( $title )
	{
		return $this->namespace_id ( $this->title_namespace ( $title ) );
	}

	public function is_main_page ( $title )
	{
		$parts = $this->title_parts ( $title );
		return $this->is_main_namespace_type ( $parts['namespace'] );
	}

	public function is_talk_page ( $title )
	{
		$parts = $this->title_parts ( $title );
		return $this->is_talk_namespace_type ( $parts['namespace'] );
	}

	public function is_special_page ( $title )
	{
		$parts = $this->title_parts ( $title );
		return $this->is_special_namespace_type ( $parts['namespace'] );
	}

	public function talk_page_title ( $main_page_title )
	{
		$parts = $this->title_parts ( $main_page_title );
		$ns_id = $this->namespace_id ( $parts['namespace'] );
		if ( ( $ns_id % 2 ) || ( $ns_id < 0 ) )
		{
			return false;
		}
		else
		{
			$parts['namespace'] = $this->namespace_basic_name ( $ns_id + 1 );
			return $this->parts_title ( $parts );
		}
	}

	public function main_page_title ( $talk_page_title )
	{
		$parts = $this->title_parts ( $talk_page_title );
		$ns_id = $this->namespace_id ( $parts['namespace'] );
		if ( ! ( $ns_id % 2 ) || ( $ns_id < 0 ) )
		{
			return false;
		}
		else
		{
			$parts['namespace'] = $this->namespace_basic_name ( $ns_id - 1 );
			return $this->parts_title ( $parts );
		}
	}

	public function maintalk_pages_titles ( $title )
	{
		if ( $this->is_main_page ( $title ) )
		{
			return array ( 'main' => $title, 'talk' => $this->talk_page_title ( $title ) );
		}
		elseif ( $this->is_talk_page ( $title ) )
		{
			return array ( 'main' => $this->main_page_title ( $title ), 'talk' => $title );
		}
		elseif ( $this->is_special_page ( $title ) )
		{
			return array ( 'special' => $title );
		}
		else
		{
			return false;
		}
	}

	public function wikititle_to_url ( $title )
	{
		return urlencode ( $this->site_server() . str_replace ( '$1', $title, $this->site_articlepath() ) );
	}

	public function url_to_wikititle ( $url )
	{
		$url = urldecode ( $url );
		$wiki_base = $this->site_server() . str_replace ( '$1', '', $this->site_articlepath() );

		if ( stripos ( $url, $wiki_base ) === 0 )
			return stristr ( $url, $wiki_base );
		else
			return false;
	}


# ---------------------------------------------------------------------------- #
# --                            Common regexes                              -- #
# ---------------------------------------------------------------------------- #


	public function regex_wikicase ( $string )
	{
		switch ( $this->site_case() )
		{
			case 'first-letter' :
				return '(?i)' . mb_substr ( $string, 0, 1, 'utf-8' ) . '(?-i)' .
					mb_substr ( $string, 1, 10000, 'utf-8' );

			default :
				return $string;
		}
	}


	protected function barsepstring ( $arg, $preg_quote = false,
		$regex_wikicase = false )
	{
		if ( is_array ( $arg ) )
		{
			$args = array();  // or else back-modification may occur sometimes (wtf?!)
			foreach ( $arg as $value )
			{
				$value = ( $preg_quote ? preg_quote ( $value ) : $value );
				$args[] = ( $regex_wikicase ? $this->regex_wikicase ( $value ) : $value );
			}
			return implode ( "|", $args );
		}
		else
		{
			return $arg;
		}
	}


	public function namespace_barsepnames ( $id_or_name, $preg_quote = false,
		$regex_wikicase = false )
	{
		return $this->barsepstring (
			$this->namespace_names_and_aliases ( $id_or_name ),
			$preg_quote, $regex_wikicase );
	}

	public function namespaces_barsepnames ( $preg_quote = false,
		$regex_wikicase = false )
	{
		return $this->barsepstring ( $this->namespaces_allnames(), $preg_quote,
			$regex_wikicase );
	}


	public function namespace_namesregex ( $id_or_name )
	{
		return '(' . $this->namespace_barsepnames ( $id_or_name, true, true ) . ')';
	}

	public function namespaces_namesregex ()
	{
		return '(' . $this->namespaces_barsepnames ( true, true ) . ')';
	}


	public function interwikis_barsepprefixes ()
	{
		return $this->barsepstring ( $this->interwikis_prefixes() );
	}

	public function interwikis_prefixesregex ()
	{
		return '(' . $this->interwikis_barsepprefixes() . ')';
	}


	public function magicword_barsepnames ( $name_or_alias )
	{
		return $this->barsepstring ( $this->magicword_allnames ( $name_or_alias ) );
	}

	public function magicword_namesregex ( $name_or_alias )
	{
		return '(' . $this->magicword_barsepnames ( $name_or_alias, true, true ) .
			')';
	}


	# ----- Misc ----- #

	public function is_interwikis_import_ok ()
	{
		return $this->param_module_parameter_type ( "import", "interwikisource" );
	}

	public function is_interwiki_import_ok ( $wiki = NULL )
	{
		$sources = $this->import_interwikis_allowed();
		if ( is_null ( $wiki ) )
			return ( ! empty ( $sources ) );
		else
			return in_array ( $wiki, $sources );
	}


}
