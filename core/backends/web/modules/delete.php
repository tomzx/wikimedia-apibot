<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Modules: Delete.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Web_Module_Delete extends Web_Module
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
					'default' => "delete",
				),
				'title' => array
				(
					'type' => "string",
					'required' => true,
				),
				'token' => array
				(
					'type' => "string",
					'varname' => "wpEditToken",
					'required' => true,
				),
				'reason' => array
				(
					'type' => "string",
					'varname' => "wpDeleteReasonRow",
				),
				'captchaword' => array
				(
					'type' => "string",
					'varname' => "",  // todo! find the real varname used!
				),
				'captchaid' => array
				(
					'type' => "string",
					'varname' => "",  // todo! find the real varname used!
				),
				'watch' => array
				(
					'type' => "boolean",
					'varname' => "wpWatch",
					'default' => false,
				),
				'confirm_b' => array
				(
					'type' => "boolean",
					'varname' => "wpConfirmB",
					'required' => true,
					'default' => true,
				),
			),
		);
	}


}
