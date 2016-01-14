<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Module: Upload.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Web_Module_Upload extends Web_Module
{

	# ----- Implemented ----- #

	protected function hardcoded_paramdesc ( $mw_version_number )
	{
		return array
		(
			'mustbeposted' => true,
			'params' => array
			(
				'action' => array
				(
					'type' => "string",
					'required' => true,
					'default' => "submit",
				),
				'title' => array
				(
					'type' => "string",
					'required' => true,
					'default' => "Special:Upload",
				),
				'source_type' => array
				(
					'type' => "string",
					'required' => true,
					'varname' => "wpSourceType",
					'default' => "file",
				),
				'token' => array
				(
					'type' => "string",
					'required' => true,
					'varname' => "wpEditToken",
				),
				'filename' => array
				(
					'type' => "string",
					'required' => true,
					'varname' => "wpDestFile",
				),
				'comment' => array
				(
					'type' => "string",
					'default' => NULL,
					'varname' => "wpComment",
				),
				'file_body' => array
				(
					'type' => "string",
					'required' => true,
					'varname' => "wpUploadFile",
				),
				'text' => array
				(
					'type' => "string",
					'default' => NULL,
					'varname' => "wpUploadDescription",
				),
				'watch' => array
				(
					'type' => "boolean",
					'varname' => "wpWatchthis",
					'default' => false,
				),
				'ignorewarnings' => array
				(
					'type' => "boolean",
					'varname' => "wpIgnoreWarning",
					'default' => false,
				),
				'upload_button' => array  // maybe not needed
				(
					'type' => "string",
					'required' => true,
					'default' => "Upload",
					'varname' => "wpUpload",
				),
			),
		);
	}


}
