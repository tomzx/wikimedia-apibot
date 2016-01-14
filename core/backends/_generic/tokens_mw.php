<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Backends: Generic: MediaWiki Tokens.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/tokens.php' );



abstract class Tokens_MediaWiki extends Tokens
{


	# ----- Getting tokens ----- #


	protected function load_edit_token ()
	{
		if ( ! $this->token_exists ( 'edit') )
			$this->set_token ( $this->fetch_edit_token(), 'edit' );

		return $this->get_token ( 'edit' );
	}


	protected function load_patrol_token ( $rcid )
	{
		if ( ! $this->token_exists ( 'patrol' ) )
			$this->set_token ( $this->fetch_patrol_token ( $rcid ), 'patrol', $rcid );

		return $this->get_token ( 'patrol', $rcid );
	}


	protected function load_rollback_token ( $title, $user = NULL )
	{
		if ( $this->info->wiki_version_number() < 12400 )
		{

			if ( $this->token_exists ( 'rollback' ) )
				$this->set_token ( $this->fetch_rollback_token ( $title, $user ),
					'rollback', "$title|$user" );

			return $this->get_token ( 'rollback', "$title|$user" );

		}
		else
		{

			if ( ! $this->token_exists ( 'rollback' ) )
				$this->set_token ( $this->fetch_rollback_token ( $title, $user ),
					'rollback' );

			return $this->get_token ( 'rollback' );

		}

	}


	protected function load_userrights_token ( $user )
	{
		if ( $this->info->wiki_version_number() < 12400 )
		{

			if ( ! $this->token_exists ( 'userrights', $user ) )
				$this->set_token ( $this->fetch_userrights_token ( $user ),
					'userrights', $user );

			return $this->get_token ( 'userrights', $user );

		}
		else
		{

			if ( ! $this->token_exists ( 'userrights' ) )
				$this->set_token ( $this->fetch_userrights_token ( $user ),
					'userrights' );

			return $this->get_token ( 'userrights' );

		}

	}


	protected function load_watch_token ()
	{
		if ( ! $this->token_exists ( 'watch' ) )
			$this->set_token ( $this->fetch_watch_token(), 'watch' );

		return $this->get_token ( 'watch' );
	}


	# ----- Public ----- #


	# --- Getting tokens --- #


	public function block_token ()
	{
		return $this->load_edit_token();
	}

	public function delete_token ()
	{
		return $this->load_edit_token();
	}

	public function edit_token ()
	{
		return $this->load_edit_token();
	}

	public function emailuser_token ()
	{
		return $this->load_edit_token();
	}

	public function import_token ()
	{
		return $this->load_edit_token();
	}

	public function move_token ()
	{
		return $this->load_edit_token();
	}

	public function patrol_token ( $rcid )
	{
		return $this->load_patrol_token ( $rcid );
	}

	public function protect_token ()
	{
		return $this->load_edit_token();
	}

	public function rollback_token ( $title, $user = NULL )
	{
		return $this->load_rollback_token ( $title, $user );
	}

	public function unblock_token ()
	{
		return $this->load_edit_token();
	}

	public function undelete_token ()
	{
		return $this->load_edit_token();
	}

	public function upload_token ()
	{
		return $this->load_edit_token();
	}

	public function userrights_token ( $user )
	{
		return $this->load_userrights_token ( $user );
	}

	public function watch_token ()
	{
		return $this->load_watch_token();
	}


	# --- Setting tokens --- #


	public function set_block_token ( $token )
	{
		return $this->set_token ( $token, 'edit' );
	}

	public function set_delete_token ( $token )
	{
		return $this->set_token ( $token, 'edit' );
	}

	public function set_edit_token ( $token )
	{
		return $this->set_token ( $token, 'edit' );
	}

	public function set_emailuser_token ( $token )
	{
		return $this->set_token ( $token, 'edit' );
	}

	public function set_import_token ( $token )
	{
		return $this->set_token ( $token, 'edit' );
	}

	public function set_move_token ( $token )
	{
		return $this->set_token ( $token, 'edit' );
	}

	public function set_patrol_token ( $token, $rcid )
	{
		return $this->set_token ( $token, 'patrol', $rcid );
	}

	public function set_protect_token ( $token )
	{
		return $this->set_token ( $token, 'edit' );
	}

	public function set_rollback_token ( $token, $title, $user = NULL )
	{
		return $this->set_token ( $token, "$title|$user" );
	}

	public function set_unblock_token ( $token )
	{
		return $this->set_token ( $token, 'edit' );
	}

	public function set_undelete_token ( $token )
	{
		return $this->set_token ( $token, 'edit' );
	}

	public function set_upload_token ( $token )
	{
		return $this->set_token ( $token, 'edit' );
	}

	public function set_userrights_token ( $token, $user )
	{
		return $this->set_token ( $token, 'userrights', $user );
	}

	public function set_watch_token ( $token )
	{
		return $this->set_token ( $token, 'watch' );
	}


	# ----- Abstract ----- #


	abstract protected function fetch_edit_token ();
	abstract protected function fetch_watch_token ();
	abstract protected function fetch_patrol_token ( $rcid );
	abstract protected function fetch_rollback_token ( $title, $user = NULL );
	abstract protected function fetch_userrights_token ( $user );


}
