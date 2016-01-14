<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Mains: Infostore.
#
#  Provides storing of different bot info in files.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #

require_once ( dirname ( __FILE__ ) . '/settings.php' );


class InfoStore
{

	protected $settings = array (
		'info_path'     => "",
		'identity_path' => "",
	);

	protected $sitename;
	protected $username;

	protected $siteuser;

	protected $paths;


	# ----- Constructor ----- #

	function __construct ( $sitename, $username, $settings )
	{
		$paths = $settings->get ( 'paths' );
		if ( isset ( $paths['info'] ) )
			$this->settings['info_path'] = $paths['info'];
		if ( isset ( $paths['identity'] ) )
			$this->settings['identity_path'] = $paths['identity'];

		$proper = $settings->get ( 'infostore' );
		if ( is_array ( $proper ) )
			$this->settings = array_merge ( $this->settings, $proper );

		if ( is_string ( $this->settings['info_path'] ) &&
			( substr ( $this->settings['info_path'], -1 ) != "/" ) )
			$this->settings['info_path'] .= "/";
		if ( is_string ( $this->settings['identity_path'] ) &&
			( substr ( $this->settings['identity_path'], -1 ) != "/" ) )
			$this->settings['identity_path'] .= "/";

		$this->settings['sitename'] = $sitename;
		$this->settings['username'] = $username;

		$this->settings['siteuser'] = $sitename . "@" . $username;
	}


	# ----- Tools ----- #


	public function sitename ()
	{
		return $this->settings['sitename'];
	}

	public function username ()
	{
		return $this->settings['username'];
	}

	public function siteuser ()
	{
		return $this->settings['siteuser'];
	}


	# ----- Reading / Writing files ----- #


	protected function path ( $pathkey )
	{
		if ( isset ( $this->settings[$pathkey] ) )
			return $this->settings[$pathkey];
		else
			return NULL;
	}


	protected function fullname ( $name, $ext, $pathkey )
	{
		$path = $this->path ( $pathkey );

		if ( is_null ( $path ) )
			return NULL;
		else
			return $path . $name . ( empty ( $ext ) ? "" : "." . $ext );
	}


	protected function exists ( $name, $ext, $pathkey )
	{
		return @file_exists ( $this->fullname ( $name, $ext, $pathkey ) );
	}

	protected function mtime ( $name, $ext, $pathkey )
	{
		return @filemtime ( $this->fullname ( $name, $ext, $pathkey ) );
	}

	protected function read ( $name, $ext, $pathkey )
	{
		$filename = $this->fullname ( $name, $ext, $pathkey );
		if ( @is_readable ( $filename ) )
			return unserialize ( @file_get_contents ( $filename ) );
		return false;
	}

	protected function write ( $name, $ext, $data, $pathkey )
	{
		$path = $this->path ( $pathkey );
		if ( is_null ( $path ) )
			return false;

		if ( ! ( @file_exists ( $path ) && @is_dir ( $path ) ) )
			if ( ! @file_exists ( $path ) )
			{
				if ( ! @mkdir ( $path ) )
				{
					$this->log ( "Cannot create a data directory named '" . $path .
						"' (reason unknown)!", LL_ERROR );
					$this->log ( "Will not be able to store the wiki info for further use!",
						LL_WARNING );
				}
			}
			else  // path exists, but is file, not dir
			{
				$this->log ( "Cannot create a data directory named '" . $path .
					"' - a file with this name exists!", LL_ERROR );
				$this->log ( "Will not be able to store the wiki info for further use!",
					LL_WARNING );
				return false;
			}

		$filename = $this->fullname ( $name, $ext, $pathkey );
		if ( @is_writable ( $filename ) ||
		     ( ! @file_exists ( $filename ) && @is_writable ( $path ) ) )
			return @file_put_contents ( $filename, serialize ( $data ) );
		return false;
	}

	protected function delete ( $name, $ext, $pathkey )
	{
		$filename = $this->fullname ( $name, $ext, $pathkey );
		if ( @is_writable ( $filename ) )
			return @unlink ( $filename );
		return false;
	}


	# ----- Site (wiki) info files ----- #

	public function exists_siteinfo ( $type )
	{
		return $this->exists ( $this->sitename(), $type, 'info_path' );
	}

	public function mtime_siteinfo ( $type, $pathkey )
	{
		return $this->mtime ( $this->sitename(), $type, 'info_path' );
	}

	public function read_siteinfo ( $type )
	{
		return $this->read ( $this->sitename(), $type, 'info_path' );
	}

	public function write_siteinfo ( $type, $data )
	{
		return $this->write ( $this->sitename(), $type, $data, 'info_path' );
	}

	public function delete_siteinfo ( $type )
	{
		return $this->delete ( $this->sitename(), $type, 'info_path' );
	}

	# ----- User info files ----- #

	public function exists_userinfo ( $type )
	{
		return $this->exists ( $this->siteuser(), $type, 'info_path' );
	}

	public function mtime_userinfo ( $type )
	{
		return $this->mtime ( $this->siteuser(), $type, 'info_path' );
	}

	public function read_userinfo ( $type )
	{
		return $this->read ( $this->siteuser(), $type, 'info_path' );
	}

	public function write_userinfo ( $type, $data )
	{
		return $this->write ( $this->siteuser(), $type, $data, 'info_path' );
	}

	public function delete_userinfo ( $type )
	{
		return $this->delete ( $this->siteuser(), $type ,'info_path');
	}


	# ----- User identity files ----- #

	public function exists_identity ( $type )
	{
		return $this->exists ( $this->siteuser(), $type, 'identity_path' );
	}

	public function mtime_identity ( $type )
	{
		return $this->mtime ( $this->siteuser(), $type, 'identity_path' );
	}

	public function read_identity ( $type )
	{
		return $this->read ( $this->siteuser(), $type, 'identity_path' );
	}

	public function write_identity ( $type, $data )
	{
		return $this->write ( $this->siteuser(), $type, $data, 'identity_path' );
	}

	public function delete_identity ( $type )
	{
		return $this->delete ( $this->siteuser(), $type, 'identity_path' );
	}


}
