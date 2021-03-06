<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Expandtemplates.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Module_Expandtemplates extends API_Module
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "expandtemplates";
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11200 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "",
			'readrights' => true,
			'mustbeposted' => true,
			'params' => array (
				'text' => array (
					'type' => "string",
					'required' => true,
				),
				'title' => array (
					'type' => "string",
					'default' => "API",
				),
			),
		);

		if ( $mwverno >= 11300 )
		{
			$paramdesc['params']['generatexml'] = array (
				'type' => "boolean",
				'default' => "false",
			);
		}

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['includecomments'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		return $paramdesc;
	}


}
