<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Import.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Module_Import extends API_Module
{

	protected $mustbeposted = true;


	# ----- Implemented ----- #

	public function modulename ()
	{
		return "import";
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11300 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "",
			'readrights' => true,
			'writerights' => true,
			'mustbeposted' => true,
			'params' => array (
				'token' => array (
					'type' => "string",
				),
				'summary' => array (
					'type' => "string",
				),
				'xml' => array (
					'type' => "string",
				),
				'interwikisource' => array (
					'type' => array (
					),
				),
				'interwikipage' => array (
					'type' => "string",
				),
				'fullhistory' => array (
					'type' => "boolean",
					'default' => false,
				),
				'templates' => array (
					'type' => "boolean",
					'default' => false,
				),
				'namespace' => array (
					'type' => "namespace",
				),
			),
		);

		return $paramdesc;
	}


}
