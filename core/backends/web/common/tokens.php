<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web Tokens obtaining / caching class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../../_generic/tokens_mw.php' );



class Tokens_Web extends Tokens_MediaWiki
{

	# ----- Fetching tokens ----- #

	protected function fetch_edit_token ()
	{
		$vars = array (
			'title' => md5 ( time() ),
			'action' => "edit",
		);
		if ( $this->exchanger->xfer ( $vars ) )
		{
			$regex = '/\<input value\=\"([^\"]+)\" name\=\"wpEditToken\"/u';
			if ( preg_match ( $regex, $this->exchanger->data, $matches ) )
				return $matches[1];
		}
		$this->log (
			"Could not fetch edit token - changing wiki info may be impossible",
			LL_WARNING );
		return false;
	}

	protected function fetch_watch_token ()
	{
		$vars = array (
			'title' => $this->info->site_mainpage(),
		);
		if ( $this->exchanger->xfer ( $vars ) )
		{
			$regex = '/\<a href\=[^\>]+action\=watch[^\>]*\>/u';
			if ( preg_match ( $regex, $this->exchanger->data, $matches ) )
				if ( preg_match ( '/token\=([^\"\&]+)[\"\&]/u', $matches[0], $extracts ) )
					return $extracts[1];
		}
		$this->log (
			"Could not fetch watch token - watching pages may be impossible",
			LL_WARNING );
		return false;
	}

	protected function fetch_patrol_token ( $rcid )
	{
		$this->exchanger->xfer ( array ( 'rcid' => $rcid ) );
		if ( preg_match ( '/\<div class\=[\'\"]patrollink[\'\"]\>(.*)\<\/div\>/u',
			$this->exchanger->data, $matches ) )

			if ( preg_match ( '/token\=([^\"\&]+)[\"\&]/u', $matches[1], $extracts ) )
				return urldecode ( $extracts[1] );

		$this->log (
			"Could not fetch patrol token - patrolling may be impossible",
			LL_WARNING );
		return NULL;
	}

	protected function fetch_rollback_token ( $title, $user = NULL )
	{
		$query = new API_Module_Query_With_Modules ( $this->exchanger, $this->info,
			$this->hooks, $this->settings->get ( "setparams" ) ); // how the hell to get it web-wise?
		$query->set_titles ( $title );
		$query->set_property_param ( "revisions", 'token', "rollback" );
		$query->xfer();

		if ( isset ( $query->data['query']['pages'] ) )
		{
			$page = reset ( $query->data['query']['pages'] );
			if ( isset ( $page['rollbacktoken'] ) )
				return $page['rollbacktoken'];
		}

		$this->log (
			"Could not fetch rollback token - rollback may be impossible",
			LL_WARNING );
		return NULL;
	}

	protected function fetch_userrights_token ( $user )
	{
		$vars = array (
			'title' => "Special:Userrights",
			'user' => $user,
		);

		if ( $this->exchanger->xfer ( $vars ) )
		{

// possibly check the data to determine version/submit format for the new rights

			if ( preg_match ( '/\<input [^\>]*name\=\"wpEditToken\"[^\>]*\>/u',
				$this->exchanger->data, $matches ) )
			{

				if ( preg_match ( '/[\s\&]value\=\"([^\"\&]+)[\"\&]/u', $matches[0], $extracts ) )
					return urldecode ( $extracts[1] );
			}

			if ( preg_match ( '/\<div class\=[\'\"]permissions-errors[\'\"]\>/u',
				$this->exchanger->data ) )
			{
				$this->log ( "Could not fetch userrights token - lack permissions",
					LL_WARNING );
				return NULL;
			}

		}

		$this->log (
			"Could not fetch userrights token - changing user rights may be impossible",
			LL_WARNING );
		return NULL;
	}


}
