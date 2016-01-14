<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Upload.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Module_Upload extends API_Module
{

	protected $mustbeposted = true;


	# ----- Implemented ----- #

	public function modulename ()
	{
		return "upload";
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11600 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "",
			'readrights' => true,
			'writerights' => true,
			'mustbeposted' => true,
			'params' => array (
				'filename' => array (
					'type' => "string",
				),
				'comment' => array (
					'type' => "string",
				),
				'text' => array (
					'type' => "string",
				),
				'token' => array (
					'type' => "string",
				),
				'watch' => array (
					'type' => "boolean",
					'default' => false,
				),
				'ignorewarnings' => array (
					'type' => "boolean",
					'default' => false,
				),
				'file' => array (
					'type' => "string",
				),
				'url' => array (
					'type' => "string",
				),
				'sessionkey' => array (
					'type' => "string",
				),
			),
		);

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['watch']['deprecated'] = true;

			$paramdesc['params']['watchlist'] = array (
				'type' => array (
					"watch",
					"preferences",
					"nochange",
				),
				'default' => "preferences",
			);
			$paramdesc['params']['stash'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		if ( $mwverno >= 11800 )
		{
			# The following three appeared in 1.17, but werent default there:
			$paramdesc['params']['asyncdownload'] = array (
				'type' => "boolean",
				'default' => false,
			);
			$paramdesc['params']['leavemessage'] = array (
				'type' => "boolean",
				'default' => false,
			);
			$paramdesc['params']['statuskey'] = array (
				'type' => "string",
			);

			$paramdesc['params']['sessionkey']['deprecated'] = true;
			$paramdesc['params']['filekey'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11900 )
		{
			$paramdesc['params']['chunk'] = array (
				'type' => "string",
			);
			$paramdesc['params']['offset'] = array (
				'type' => "string",
			);
			$paramdesc['params']['filesize'] = array (
				'type' => "string",
			);
		}

		return $paramdesc;
	}


}
