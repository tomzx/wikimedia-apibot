<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Module: Patrol.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Web_Module_Patrol extends Web_Module
{

	# ----- Implemented ----- #

	protected function hardcoded_paramdesc ( $mw_version_number )
	{
		return array
		(
//			'mustbeposted' => true,
			'params' => array
			(
				'action' => array
				(
					'type' => "string",
					'required' => true,
					'default' => "markpatrolled",
				),
				'title' => array
				(
					'type' => "string",
					'default' => NULL,
				),
				'token' => array
				(
					'type' => "string",
					'required' => true,
				),
				'rcid' => array
				(
					'type' => "integer",
					'required' => true,
				),
			),
		);
	}


}
