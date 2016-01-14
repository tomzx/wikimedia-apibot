<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Wikifiles storage: File versions full archive.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Wikifiles_Archive extends Wikifiles_GenericStorage
{

	# ----- Tools ----- #


	protected function file_timestamp ( $file )
	{
		if ( is_array ( $file ) && isset ( $file['timestamp'] ) )
			return $file['timestamp'];
		elseif ( is_object ( $file ) && isset ( $file->timestamp ) )
			return $file->timestamp;

		return NULL;
	}


	# ----- Overriding ----- #


	protected function filename_full ( $file )
	{
		$filename = parent::filename_full ( $file );

		$version = $this->file_timestamp ( $file );
		if ( ! is_null ( $version ) )
			$filename .= '/' .
				substr ( $version, 0, 10 ) . ' ' . substr ( $version, 11, 8 );

		return $filename;
	}


	protected function create_path ( $file )
	{
		if ( parent::create_path ( $file ) )
		{
			$dirname = dirname ( $this->filename_full ( $file ) );
			if ( file_exists ( $dirname ) )
			{
				return true;
			}
			else
			{
				$this->log ( "Creating subpath '$dirname'", LL_INFO );
				if ( @mkdir ( $dirname ) )
					return true;
				else
					$this->log ( "Could not create subpath '$dirname'!", LL_ERROR );
			}
		}

		return false;
	}


	public function read ( $file, $timestamp = NULL )
	{
		if ( is_null ( $timestamp ) )
			$timestamp = $this->file_timestamp ( $file );

		if ( empty ( $timestamp ) )
			$timestamp = $this->last_file_version ( $file );
		if ( empty ( $timestamp ) )
		{
			$this->log ( "Cannot read " . $this->file_name ( $file ) .
				" - no versions!",
				LL_ERROR );
			return false;
		}

		if ( is_array ( $file ) )
			$file['timestamp'] = $timestamp;
		elseif ( is_object ( $file ) )
			$file->timestamp = $timestamp;

		return parent::read ( $file );
	}


	# exists() will return existence of specific version if timestamp persent,
	# or existence of a version at all if timestamp not present.


	public function adopt ( $file, $wfstorage, $timestamp = NULL )
	{
		if ( ( $timestamp == "" ) || ( $timestamp == "*" ) )
		{
			$versions = $wfstorage->file_versions ( $file );
			foreach ( $versions as $version )  // version is actually file timestamp

				if ( ! $this->adopt ( $file, $wfstorage, $version ) )
					return false;

			return true;
		}
		else
		{
			$file = $wfstorage->read ( $file, $timestamp );

			if ( $file === false )
				return false;
			else
			{
				$result = $this->append ( $file );
				if ( $result )
					$this->log ( "  (adopted from storage " . $wfstorage->basepath . ")",
						LL_DEBUG );
			}
		}
	}


	# ----- New ----- #

	public function file_versions ( $file )
	{
		$filepath = parent::filename_full ( $file );

		if ( ! is_dir ( $filepath ) )
			return array();

		$dirlist = @scandir ( $filepath );
		$file_versions = array();
		foreach ( $dirlist as $entry )
			if ( preg_match ( '/^\d\d\d\d\-\d\d\-\d\d \d\d\:\d\d\:\d\d$/u', $entry ) )
				$file_versions[] = $entry;

		return $file_versions;
	}


	public function last_file_version ( $file )
	{
		$versions = $this->file_versions ( $file );

		if ( is_array ( $versions ) )
			return end ( $versions );

		return false;
	}


}
