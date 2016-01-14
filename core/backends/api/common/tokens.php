<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Backend: Tokens.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../../_generic/tokens_mw.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/query_with_modules.php' );



class Tokens_API extends Tokens_MediaWiki
{


	# ----- Overriding ----- #

	# Patrol token does not need $rcid in the API backend


	protected function load_patrol_token ( $rcid )
	{
		if ( ! $this->token_exists ( 'patrol' ) )
			$this->set_token ( $this->fetch_patrol_token ( $rcid ), 'patrol' );

		return $this->get_token ( 'patrol' );
	}


	public function set_patrol_token ( $token, $rcid )
	{
		return $this->set_token ( $token, 'patrol' );
	}


	# ----- Fetching tokens ----- #


	protected function fetch_standard_tokens ()
	{
		if ( $this->info->wiki_version_number() < 12400 )
		{

			$title = $this->info->site_mainpage();

			$query = new API_Module_Query_With_Modules ( $this->exchanger, $this->info,
				$this->hooks, $this->settings->get ( "setparams" ) );
			$query->set_titles ( $title );
			if ( $query->is_property_paramvalue_ok ( "info", 'token', "edit" ) )
				$query->set_property_param ( "info", 'token', "edit" );
			if ( $query->is_property_paramvalue_ok ( "info", 'token', "watch" ) )
				$query->set_property_param ( "info", 'token', "watch" );
			$query->xfer();

			if ( isset ( $query->data['query']['pages'] ) )
			{
				$page = reset ( $query->data['query']['pages'] );
				if ( isset ( $page['edittoken'] ) )
				{
					$this->tokens['edit'] = $page['edittoken'];
				}
				else
				{
					if ( $query->is_property_paramvalue_ok ( "info", 'token', "edit" ) )
						$this->log ( "Edit token not supplied - editing may be impossible",
						LL_WARNING );
				}
				if ( isset ( $page['watchtoken'] ) )
				{
					$this->tokens['watch'] = $page['watchtoken'];
				}
				else
				{
					if ( $query->is_property_paramvalue_ok ( "info", 'token', "watch" ) )
						$this->log ( "Watch token not supplied - watching may be impossible",
							LL_WARNING );
				}

				return true;
			}
			else
			{
				$this->log (
					"Could not fetch standard tokens - changing wiki info may be impossible",
					LL_WARNING );
				return false;
			}

		}
		else
		{

			$token_names = array ( "csrf", "watch", "patrol", "rollback", "userrights" );

			$query = new API_Module_Query_With_Modules ( $this->exchanger, $this->info,
				$this->hooks, $this->settings->get ( "setparams" ) );
			$query->set_meta_param ( "tokens", 'type', $token_names );
			$query->xfer();

			if ( isset ( $query->data['query']['tokens']['csrftoken'] ) )
				$this->tokens['edit'] =
					$query->data['query']['tokens']['csrftoken'];
			if ( isset ( $query->data['query']['tokens']['patroltoken'] ) )
				$this->tokens['patrol'] =
					$query->data['query']['tokens']['patroltoken'];
			if ( isset ( $query->data['query']['tokens']['rollbacktoken'] ) )
				$this->tokens['rollback'] =
					$query->data['query']['tokens']['rollbacktoken'];
			if ( isset ( $query->data['query']['tokens']['userrightstoken'] ) )
				$this->tokens['userrights'] =
					$query->data['query']['tokens']['userrightstoken'];
			if ( isset ( $query->data['query']['tokens']['watchtoken'] ) )
				$this->tokens['watch'] =
					$query->data['query']['tokens']['watchtoken'];

			return true;
		}


	}

	protected function fetch_edit_token () {
		if ( $this->fetch_standard_tokens() )
			if ( isset ( $this->tokens['edit'] ) )
				return $this->tokens['edit'];

		return false;
	}

	protected function fetch_watch_token () {
		if ( $this->fetch_standard_tokens() )
			if ( isset ( $this->tokens['watch'] ) )
				return $this->tokens['watch'];

		return false;
	}



	protected function fetch_patrol_token ( $rcid )  // $rcid is not used here
	{
		$query = new API_Module_Query_With_Modules ( $this->exchanger, $this->info,
			$this->hooks, $this->settings ( "setparams" ) );
		$query->set_list_param ( "recentchanges", 'token', "patrol" );
		$query->set_list_param ( "recentchanges", 'limit', 1 );
		$query->xfer();

		if ( isset ( $query->data['query']['recentchanges'] ) )
		{
			$rc = reset ( $query->data['query']['recentchanges'] );
			if ( isset ( $rc['patroltoken'] ) )
				return $rc['patroltoken'];
		}

		$this->log (
			"Could not fetch patrol token - patrolling may be impossible",
			LL_WARNING );
		return NULL;
	}

	protected function fetch_rollback_token ( $title, $user = NULL )
	{
		if ( $this->info->wiki_version_number() < 12400 )
		{

			$query = new API_Module_Query_With_Modules ( $this->exchanger, $this->info,
				$this->hooks, $this->settings->get ( "setparams" ) );
			$query->set_titles ( $title );
			if ( ! is_null ( $user ) )
				$query->set_property_param ( "revisions", 'user', $user );
			$query->set_property_param ( "revisions", 'token', "rollback" );
			$query->xfer();

			if ( isset ( $query->data['query']['pages'] ) )
			{
				$page = reset ( $query->data['query']['pages'] );
				if ( isset ( $page['revisions'] ) )
				{
					$revision = reset ( $page['revisions'] );
					if ( isset ( $revision['rollbacktoken'] ) )
						return $revision['rollbacktoken'];
				}
			}

		}
		else
		{

			if ( $this->fetch_standard_tokens() )
				if ( isset ( $this->tokens['rollback'] ) )
					return $this->tokens['rollback'];

		}

		$this->log (
			"Could not fetch rollback token - rollback may be impossible",
			LL_WARNING );
		return false;
	}

	protected function fetch_userrights_token ( $user )
	{
		if ( $this->info->wiki_version_number() < 12400 )
		{

			$query = new API_Module_Query_With_Modules ( $this->exchanger, $this->info,
				$this->hooks, $this->settings->get ( "setparams" ) );
			$query->set_list_param ( "users", 'user', $user );
			$query->set_list_param ( "users", 'token', "userrights" );
			$query->xfer();

			if ( isset ( $query->data['query']['users'] ) )
			{
				$user = reset ( $query->data['query']['users'] );
				if ( isset ( $user['userrightstoken'] ) )
					return $user['userrightstoken'];
			}

		}
		else
		{

			if ( $this->fetch_standard_tokens() )
				if ( isset ( $this->tokens['userrights'] ) )
					return $this->tokens['userrights'];

		}

		$this->log (
			"Could not fetch userrights token - changing user rights may be impossible",
			LL_WARNING );
		return false;
	}


}
