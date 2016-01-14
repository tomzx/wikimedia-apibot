<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Writer: File: From wiki
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Writer_WikiFile extends Writer_File
{


	public $path;


	# ----- Overriding ----- #


	protected function process_data ( &$signal )
	{
		if ( parent::process_data ( $signal ) === false )
			return false;

		if ( ! isset ( $this->path ) )
		{
			$this->log ( 'Property $path is not set - exitting!', LL_PANIC );
			die();
		}

		$element = $signal->data_element ( $this->default_data_key );
		if ( is_object ( $element ) && $element instanceof File )
			$element = $element->data();

		if ( is_array ( $element ) && isset ( $element['title'] ) )
		{
			$filename = $this->core->info->title_pagename ( $element['title'] );
			if ( isset ( $element['body'] ) )
			{
				$result = file_put_contents ( $filename, $element['body'] );
				$this->log ( "Wrote file $filename" );
			}
			elseif ( isset ( $element['body_link'] ) )
			{
				$result = $this->copy_file ( $element['body_link'], $filename );
				if ( isset ( $element['body_link_is_tempfile'] ) &&
					$element['body_link_is_tempfile'] )
				{
					@unlink ( $element['body_link'] );
				}
				$result = true;
				$this->log ( "Wrote file $filename" );
			}
			else
			{
				$this->log ( "Could not determine $filename's body - cannot write it!",
					LL_ERROR );
				$result = false;
			}
		}
		else
		{
			$result = false;
			$filename = "";
			$this->log ( "Could not determine a title for a file - cannot write it!",
				LL_ERROR );
		}

		$this->set_jobdata ( $result, array ( 'filename' => $filename ) );

		return true;
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'path' );

		return $params;
	}


	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'path' );

		return parent::set_params ( $params );
	}


}
