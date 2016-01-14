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


class Web_Module_Block extends Web_Module
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
				'action' => array  // not sure if it is needed, at least in some versions
				(
					'type' => "string",
					'required' => true,
					'default' => "",
				),
				'title' => array
				(
					'type' => "string",
					'required' => true,
					'default' => "Special:Block",
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
#					'varname' => "wpBlockAddress",
					'varname' => "wpTarget",
					'required' => true,
				),
				'reason_dropdown' => array
				(
					'type' => "string",
#					'varname' => "wpBlockReasonList",
					'varname' => "wpReason",
					'default' => "other",
					'required' => true,
				),
				'reason' => array
				(
					'type' => "string",
#					'varname' => "wpBlockReason",
					'varname' => "wpReason-other",
				),
				'expiry_dropdown' => array
				(
					'type' => array (
						"other",
						"1 day",
						"3 days",
						"1 week",
						"2 weeks",
						"1 month",
						"3 months",
						"6 months",
						"1 year",
						"infinite",
					),
#					'varname' => "wpBlockExpiry",
					'varname' => "wpExpiry",
					'default' => "other",
					'required' => true,
				),
				'expiry' => array
				(
					'type' => "timestamp",
#					'varname' => "wpBlockOther",
					'varname' => "wpExpiry-other",
					'default' => "never",
				),
				'anononly' => array
				(
					'type' => "boolean",
					'varname' => "wpAnonOnly",
					'required' => true,
					'default' => false,
				),
				'nocreate' => array
				(
					'type' => "boolean",
					'varname' => "wpCreateAccount",
					'required' => true,
					'default' => false,
				),
				'autoblock' => array
				(
					'type' => "boolean",
					'varname' => "wpEnableAutoblock",
					'required' => true,
					'default' => false,
				),
				'noemail' => array
				(
					'type' => "boolean",
#					'varname' => "wpEmailBan",
					'varname' => "wpDisableEmail",
					'required' => true,
					'default' => false,
				),
				'hardblock' => array
				(
					'type' => "boolean",
					'varname' => "wpHardBlock",
					'required' => true,
					'default' => false,
				),
				'previous_target' => array
				(
					'type' => "string",
					'varname' => "wpPreviousTarget",
					'required' => true,
					'default' => "",
				),
				'confirm' => array
				(
					'type' => "string",
					'varname' => "wpConfirm",
					'required' => true,
					'default' => "",
				),
			),
		);
	}


}
