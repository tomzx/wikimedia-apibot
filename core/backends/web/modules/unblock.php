<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Module: Unblock.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Web_Module_Unblock extends Web_Module
{

	# ----- Implemented ----- #

	protected function hardcoded_paramdesc ( $mw_version_number )
	{
// todo!
// MW form variables seems to have changed at least once within the reasonably
// recent versions. This version is tested with with MW 1.18.
// Must check the MW version and return a paramdesc tailored to it.
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
					'default' => "Special:BlockList",
				),
				'token' => array
				(
					'type' => "string",
					'varname' => "wpEditToken",
					'required' => true,
				),
				'user' => array
				(
					'type' => "string",
					'varname' => "wpTarget",
					'required' => true,
				),
				'reason' => array
				(
					'type' => "string",
					'varname' => "wpReason",
				),
			),
		);
	}


}
