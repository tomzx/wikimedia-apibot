<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Wikifiles storage: Generic.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../../../core/data/_generic.php' );


abstract class Wikifiles_GenericStorage
{

	public $basepath;
	public $create_basepath = false;  // create it if it doesn't exist

	public $subpath_levels = 2;  // number of inlayed subpaths

	public $overwrite_files = true;  // should append() overwrite already existing files?


	public $body_links_only = false;  // if true, read() sets body_link instead of body


	public $xml_file_extension = "info.xml";  // unset or set to NULL to not write xml files
	public $extra_xml_fields = NULL;
	public $xml_compression = NULL;


	protected $core;


	protected $listed_paths = array();


	# ----- Constructor ----- #


	function __construct ( $core, $basepath = NULL )
	{
		$this->core = $core;
		if ( ! is_null ( $basepath ) )
			$this->basepath = $basepath;
	}


	# ----- Tools ----- #


	protected function log ( $message, $loglevel = LL_INFO, $preface = "" )
	{
		return $this->core->log->log ( $message, $loglevel,
			$preface . "Wikifiles_Storage: " );
	}


	# --- Filenames and paths --- #


	protected function file_name ( $file )
	{
		if ( is_array ( $file ) )
			$filename = $file['title'];
		elseif ( is_object ( $file ) )
			$filename = $file->title;
		elseif ( is_string ( $file ) )
			$filename = $file;
		else
		{
			$this->log ( "Could not determine a file name!", LL_PANIC );
			die();
		}

		$filename = str_replace ( '/', '%%slash%%', $filename );

		return $this->core->info->title_name ( $filename );
	}


	protected function file_path ( $file )
	{
		if ( $this->subpath_levels >= 10 )
		{
			$this->log ( "Parameter subpath_levels should not exceed 10! Exitting...",
				LL_PANIC );
			$this->log ( "  (Do you actually need more than 10 nested subpaths?!?!)",
				LL_PANIC );
			die();
		}

		$filename = $this->file_name ( $file );
		$sha1 = sha1 ( $filename );

		$path = $this->basepath;

		for ( $level = 0 ; $level < $this->subpath_levels ; $level++ )
		{
			$path .= '/' . substr ( $sha1, 0, 2 );
			$sha1 = substr ( $sha1, 2 );
		}

		return $path;
	}


	protected function filename_full ( $file )
	{
		return $this->file_path ( $file ) . '/' . $this->file_name ( $file );
	}


	protected function filename_xmldesc ( $filename )
	{
		$name = $filename . "." . $this->xml_file_extension;

		if ( ! empty ( $this->xml_compression ) )
			$name .= "." . $this->xml_compression;

		return $name;
	}


	protected function create_path ( $filename )
	{
		$filename = $this->file_name ( $filename );
		$sha1 = sha1 ( $filename );

		$path = $this->basepath;
		if ( ! file_exists ( $path ) && $this->create_basepath )
			if ( ! @mkdir ( $path ) )
			{
				$this->log ( "Could not create the storage basepath ($path)", LL_PANIC );
				die();
			}

		for ( $level = 0 ; $level < $this->subpath_levels ; $level++ )
		{
			$path .= '/' . substr ( $sha1, 0, 2 );
			$sha1 = substr ( $sha1, 2 );

			if ( ! file_exists ( $path ) )
			{
				$this->log ( "Creating subpath '" . $path . "'" );
				if ( ! mkdir ( $path ) )
					return false;
			}
		}

		return true;
	}


	protected function remove_empty_path ( $filename )
	{
		$path = $this->file_path ( $filename );
		for ( $counter = 0; $counter <= $this->subpath_levels; $counter++ )
		{
			if ( @file_exists ( $path ) )
			{
				$files = array_diff ( @scandir ( $path ), array ( '.', '..' ) );
				if ( empty ( $files ) )
					if ( ! @unlink ( $path ) )
					{
						$this->log ( "Could not delete path '$path'!", LL_ERROR );
						return false;
					}
			}
			$path = dirname ( $path );
		}
		return true;
	}


	# ----- Compression / Uncompression ----- #


	function compress ( $body, $compression_type )
	{
		switch ( strtolower ( $compression_type ) )
		{
			case NULL :
			case "" :
				return $body;
			case "gzip" :
				if ( ! function_exists ( "gzencode" ) )
				{
					$this->log ( "Unsupported compression method: " . $compression_type .
						" - exitting!", LL_PANIC );
					die();
				}
				return gzencode ( $body );
			case "zlib" :
				if ( ! function_exists ( "gzcompress" ) )
				{
					$this->log ( "Unsupported compression method: " . $compression_type .
						" - exitting!", LL_PANIC );
					die();
				}
				return gzcompress ( $body );
			case "bzip2" :
				if ( ! function_exists ( "bzcompress" ) )
				{
					$this->log ( "Unsupported compression method: " . $compression_type .
						" - exitting!", LL_PANIC );
					die();
				}
				return bzcompress ( $body );
			default :
				$this->log ( "Unknown compression specified: " . $compression_type .
					" - exitting!", LL_PANIC );
				die();
		}
	}


	function uncompress ( $body, $compression_type )
	{
		switch ( strtolower ( $compression_type ) )
		{
			case NULL :
			case "" :
				return $body;
			case "gzip" :
				if ( ! function_exists ( "gzdecode" ) )
				{
					$this->log ( "Unsupported compression method: " . $compression_type .
						" - exitting!", LL_PANIC );
					die();
				}
				return gzdecode ( $body );
			case "zlib" :
				if ( ! function_exists ( "gzuncompress" ) )
				{
					$this->log ( "Unsupported compression method: " . $compression_type .
						" - exitting!", LL_PANIC );
					die();
				}
				return gzuncompress ( $body );
			case "bzip2" :
				if ( ! function_exists ( "bzuncompress" ) )
				{
					$this->log ( "Unsupported compression method: " . $compression_type .
						" - exitting!", LL_PANIC );
					die();
				}
				return bzdecompress ( $body );
			default :
				$this->log ( "Unknown compression specified: " . $compression_type .
					" - exitting!", LL_PANIC );
				die();
		}
	}


	# ----- Array <--> XML conversion tools (quick & dirty) ----- #


	protected function array_to_xml_element ( $array, &$xml_element )
	{
		$nonwritable_fields = array ( 'revisions', 'body', 'body_link',
			'body_link_is_tempfile' );

		foreach ( $array as $key => $value )
			if ( ! in_array ( $key, $nonwritable_fields ) )
				if ( is_array ( $value ) )
				{
					if ( is_numeric ( $key ) )
						$sub = $xml_element->addChild ( "item$key" );
					else
						$sub = $xml_element->addChild ( "$key" );

					$this->array_to_xml_element ( $value, $sub );
				}
				else
				{
					$xml_element->addChild ( $key, htmlspecialchars ( $value ) );
				}
	}


	protected function file_to_xml ( $file )
	{
		if ( is_object ( $file ) && ( $file instanceof Dataobject ) )
			$file = $file->data();

		if ( is_array ( $file ) )
		{
			if ( is_array ( $this->extra_xml_fields ) )
				$file = array_merge ( $file, $this->extra_xml_fields );

			$xml_element = new SimpleXMLElement (
				"<?xml version=\"1.0\"?><file></file>" );
			$this->array_to_xml_element ( $file, $xml_element );
			return $this->compress ( $xml_element->asXML(), $this->xml_compression );
		}
		else
		{
			$this->log ( "Cannot generate file desc XML: Bad file data", LL_ERROR );
			return false;
		}
	}


	protected function xml_element_to_array ( $xml_element )
	{
		$array = (array)$xml_element;

		foreach ( $array as $key => $value )
			if ( is_object ( $value ) &&
				( strpos ( get_class ( $value), "SimpleXML" ) !== false ) )

				$array[$key] = $this->xml_element_to_array ( $value );

		return $array;
	}


	protected function xml_to_array ( $xml )
	{
		$xml_element = new SimpleXMLElement (
			$this->uncompress ( $xml, $this->xml_compression ) );
		return $this->xml_element_to_array ( $xml_element );
	}


	# --- Listing files --- #


	protected function list_element ( &$subs, $sub_level, $path )
	{
		if ( ! is_dir ( $path ) )
			return false;

		if ( ! isset ( $subs[$sub_level] ) )
		{
			$subs[$sub_level] = @scandir ( $path );

			if ( $subs[$sub_level] === false )
			{
				$this->log ( "Could not read path '$path' - exitting!", LL_PANIC );
				die();
			}

			while ( substr ( $subs[$sub_level][0], 0, 1 ) == "." )
				array_shift ( $subs[$sub_level] );
		}

		while ( true )
		{
			if ( empty ( $subs[$sub_level] ) )
			{
				unset ( $subs[$sub_level] );
				return false;
			}

			if ( $sub_level )
			{

				$subdir = current ( $subs[$sub_level] );
				if ( ! strlen ( $subdir ) == 2 )
					return false;
				$result = $this->list_element ( $subs, $sub_level - 1,
					$path . '/' . $subdir );

				if ( $result === false )
				{
					array_shift ( $subs[$sub_level] );
				}
				else
				{
					return $result;
				}

			}
			else
			{

				return array_shift ( $subs[$sub_level] );

			}
		}

	}


	// On call will return the next matching file in the storage, or false on end
	public function list_file ( $glob = "*" )
	{
		do {
			$file = $this->list_element ( $this->listed_paths, $this->subpath_levels,
				$this->basepath );
		} while ( ( $file === false ) || fnmatch ( $glob, $file->name ) );

		$file->name = str_replace ( '%%slash%%', '/', $file->name );

		return $file;
	}


	// Resets the files listing pointer
	public function reset_files_listing ()
	{
		$this->listed_paths = array();
	}


	# --- Reading / Writing files --- #


	protected function read_file ( &$file, $filename )
	{
		if ( $this->body_links_only )
		{

			if ( is_array ( $file ) )
				$file['body_link'] = $filename;
			elseif ( is_object ( $file ) && $file instanceof File )
				$file->body_link = $filename;
			else
			{
				$this->log ( "Bad file object format - most probably programming error!",
					LL_PANIC );
				die();
			}

		}
		else
		{

			if ( is_array ( $file ) )
			{
				$file['body'] = file_get_contents ( $filename );
				if ( $file['body'] === false )
					return false;
			}
			elseif ( is_object ( $file ) && $file instanceof File )
			{
				$file->body = file_get_contents ( $filename );
				if ( $file->body === false )
					return false;
			}
			else
			{
				$this->log ( "Bad file object format - most probably programming error!",
					LL_PANIC );
				die();
			}

		}

		if ( isset ( $this->xml_file_extension ) &&
			! is_null ( $this->xml_file_extension ) )
		{
			$filename_xmldesc = $this->filename_xmldesc ( $filename );
			$contents = file_get_contents ( $filename_xmldesc );
			if ( $contents === false )
			{
				$this->log ( "Could not read file's description (XML)!", LL_ERROR );
				return false;
			}

			$data = $this->xml_to_array ( $contents );

			if ( is_array ( $file ) )
				$file = array_merge ( $file, $data );
			elseif ( is_object ( $file ) && $file instanceof File )
			{
				$file = new File ( $this->core, array_merge ( $file->data(), $data ) );
			}

		}
		return true;
	}


	protected function write_file ( $file, $filename )
	{
		if ( is_object ( $file ) && ( $file instanceof File ) )
			$file = $file->data();

		if ( isset ( $file['body'] ) )
		{
			$filesize = file_put_contents ( $filename, $file['body'] );
		}
		elseif ( isset ( $file['body_link'] ) )
		{
			$fp_in = @fopen ( $file['body_link'], 'r' );
			if ( $fp_in === false )
			{
				$this->log ( "Could not open file " . $file['body_link'] . " - exitting!",
					LL_PANIC );
				die();
			}
			$fp_out = @fopen ( $filename, 'w+' );
			if ( $fp_in === false )
			{
				$this->log ( "Could not open file " . $filename . " - exitting!",
					LL_PANIC );
				die();
			}

			$filesize = 0;
			do {
				$buf = @fread ( $fp_in, 65536 );
				$len = @fwrite ( $fp_out, $buf );
				$filesize += $len;
			} while ( $len );

			if ( @fclose ( $fp_in ) === false )
			{
				$this->log ( "Could not close file " . $file['body_link'] . " - exitting!",
					LL_PANIC );
				die();
			}
			if ( @fclose ( $fp_out ) === false )
			{
				$this->log ( "Could not close file " . $filename . " - exitting!",
					LL_PANIC );
				die();
			}

			if ( isset ( $file['body_link_is_tempfile'] ) &&
				$file['body_link_is_tempfile'] )

				@unlink ( $file['body_link'] );

		}
		else
		{
			$this->log ( "Could not determine the body for $filename - " .
				"refusing to write the file!", LL_ERROR );
				return false;
		}

		if ( $filesize !== false )
		{
			$this->log ( "Wrote wiki file '$filename' ($filesize bytes)" );

			if ( isset ( $this->xml_file_extension ) &&
				! is_null ( $this->xml_file_extension ) )
			{
				$filename_xmldesc = $this->filename_xmldesc ( $filename );
				$xml_body = $this->file_to_xml ( $file );

				if ( $xml_body == false )
				{
					$this->log ( "Could not make file's description (XML)!", LL_ERROR );
				}
				else
				{
					$filesize = file_put_contents ( $filename_xmldesc, $xml_body );
					if ( $filesize !== false )
						$this->log ( "Wrote also a '" . basename ( $filename_xmldesc ) .
							"' file ($filesize bytes)", LL_DEBUG );
				}
			}

			return true;
		}
		else
		{
			$this->log ( "Could not write the file body for $filename!", LL_PANIC );
			die();
		}
	}


	# ----- Public ----- #


	public function read ( $file )
	{
		if ( is_string ( $file ) )
		{
			$title = $file;
			$namespace = $this->core->info->title_namespace ( $filename );
			if ( empty ( $namespace ) )
				$title = $this->core->info->namespace_name ( 'File' ) . ':' . $file;

			$file = new File (
				$this->core,
				array ( 'title' => $title )
			);
		}

		$filename = $this->filename_full ( $file );

		$this->read_file ( $file, $filename );

		return $file;
	}


	public function append ( $file )
	{
		$filename = $this->filename_full ( $file );

		if ( $this->create_path ( $file ) )
		{
			if ( is_object ( $file ) && $file instanceof File )
				$file = $file->data();

			if ( ! is_array ( $file ) )
			{
				$this->log ( "Could not determine the type of the file data supplied!",
					LL_PANIC );
				die();
			}

			if ( ! ( isset ( $file['body'] ) ||
				( isset ( $file['body_link'] ) && file_exists ( $file['body_link'] ) ) ) )
			{
				$filename = $this->file_name ( $file );
				$this->log ( "Could not determine the body for $filename - " .
					"refusing to write the file info!", LL_ERROR );
				$this->remove_empty_path ( $filename );
				return false;
			}

			if ( file_exists ( $filename ) && ! $this->overwrite_files )
			{
				$this->log ( "Will not overwrite already existing file '$filename'!" );
				return true;
			}
			else
			{
				$result = $this->write_file ( $file, $filename );
				if ( ! $result )
				{
					$this->remove_empty_path ( $filename );
				}
				return $result;
			}

		}
		else
		{

			$this->log ( "Could not create the path for $filename!", LL_PANIC );
			die();

		}

		return false;
	}


	public function modify ( $file )
	{
		$this->delete ( $file );
		$this->append ( $file );
	}


	public function delete ( $file )
	{
		$filename = $this->filename_full ( $file );
		if ( file_exists ( $filename ) )
		{
			$result = @unlink ( $filename );

			$filename_xmldesc = $this->filename_xmldesc ( $filename );
			if ( $result && file_exists ( $filename_xmldesc ) )
				$result = @unlink ( $filename_xmldesc );

			while ( true )
			{
				$filename = dirname ( $filename );
				if ( $filename == $this->basepath )
					break;
				$entries = @scandir ( $filename );
				unset ( $entries[array_search ( '.', $entries )] );
				unset ( $entries[array_search ( '..', $entries )] );
				if ( empty ( $entries ) )
				{
					$this->log ( "Removing empty path '$filename'..." );
					if ( ! rmdir ( $filename ) )
					{
						$this->log ( "Could not remove path '$filename'!", LL_WARNING );
						break;
					}
				}
			}

			return $result;
		}
		else
		{
			return true;
		}
	}


	public function rename ( $file, $new_name )
	{
		$filename = $this->filename_full ( $file );
		$new_name = dirname ( $new_name ) . '/' . basename ( $filename );
		if ( file_exists ( $filename ) )
		{
			$result = @rename ( $filename, $new_name );

			$filename_xmldesc = $this->filename_xmldesc ( $filename );
			if ( $result && file_exists ( $filename_xmldesc ) )
			{
				$new_name_xmldesc = $this->filename_xmldesc ( $new_name );
				$result = @rename ( $filename_xmldesc, $new_name_xmldesc );
			}

			return $result;
		}
		else
		{
			return true;
		}
	}


	public function exists ( $file )
	{
		return file_exists ( $this->filename_full ( $file ) );
	}


	public function adopt ( $file, $wfstorage, $move = false )
	{
		$file = $wfstorage->read ( $file );

		if ( $file === false )
			return false;
		else
		{
			$result = $this->append ( $file );
			if ( $result )
			{
				$this->log ( "  (adopted from storage " . $wfstorage->basepath . ")",
					LL_DEBUG );
				if ( $move )
					if ( $wfstorage->delete ( $file ) )
						$this->log ( "  (and deleted from there)", LL_DEBUG );
					else
						$this->log ( "Oops! Could not delete the file from the old storage!",
							LL_ERROR );
			}
			return $result;
		}
	}


}
