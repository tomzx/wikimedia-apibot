<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Module: Userrights.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Web_Module_Userrights extends Web_Module
{

	# ----- Implemented ----- #

	protected function hardcoded_paramdesc ( $mw_version_number )
	{
// todo!
// this version of paramdesc is good only for old MW versions (check the diap!).
// must see how the things changed with the time and make appropriate paramdescs
// and userrights() code!
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
					'default' => "Special:Userrights",
				),
				'user' => array
				(
					'type' => "string",
					'default' => NULL,
				),
				'reason' => array
				(
					'type' => "string",
					'default' => NULL,
					'varname' => "user-reason",
				),
				'token' => array
				(
					'type' => "string",
					'required' => true,
					'varname' => "wpEditToken",
				),
				'available' => array
				(
					'type' => array(),
					'multi' => true,
				),
				'removable' => array
				(
					'type' => array(),
					'multi' => true,
				),
			),
		);
	}


}
