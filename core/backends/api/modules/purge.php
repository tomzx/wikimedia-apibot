<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Purge.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Module_Purge extends API_Module
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "purge";
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11200 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "",
			'readrights' => true,
			'writerights' => true,
			'params' => array (
				'titles' => array (
					'type' => "string",
					'multi' => true,
					'limit' => 50,
				),
			),
		);

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['forcelinkupdate'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		if ( $mwverno >= 11900 )
		{
			$paramdesc['params']['pageids'] = array (
				'type' => "integer",
				'multi' => true,
				'limit' => 50,
			);
			$paramdesc['params']['revids'] = array (
				'type' => "integer",
				'multi' => true,
				'limit' => 50,
			);
		}

		return $paramdesc;
	}


}
