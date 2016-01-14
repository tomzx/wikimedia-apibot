<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Logevents.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Logevents extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "logevents";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 10900 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "le",
			'generator' => true,
			'params' => array (
				'type' => array (
					'type' => array (
						"block",
						"protect",
						"rights",
						"delete",
						"upload",
						"move",
						"import",
						"renameuser",
						"newusers",
						"makebot",
					),
				),
				'start' => array (
					'type' => "timestamp",
				),
				'end' => array (
					'type' => "timestamp",
				),
				'dir' => array (
					'type' => array (
						"newer",
						"older",
					),
					'default' => "older",
				),
				'user' => array (
					'type' => "string",  // MW API says type is string, not user.
				),
				'title' => array (
					'type' => "string",
				),
				'limit' => array (
					'type' => "limit",
					'max' => 500,
					'default' => 10,
				),
			),
		);

		if ( $mwverno >= 11100 )
		{
			$paramdesc['params']['prop'] = array (
				'type' => array (
					"ids",
					"title",
					"type",
					"user",
					"timestamp",
					"comment",
					"details",
				),
				'multi' => true,
				'default' => "ids|title|type|user|timestamp|comment|details",
				'limit' => 50,
			);
		}

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['prop']['type'][] = "parsedcomment";
			$paramdesc['params']['prop']['type'][] = "tags";

			$paramdesc['params']['tag'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['action'] = array (
				'type' => array (
					"block/block",
					"block/unblock",
					"block/reblock",
					"protect/protect",
					"protect/modify",
					"protect/unprotect",
					"protect/move_prot",
					"rights/rights",
					"rights/autopromote",
					"upload/upload",
					"upload/overwrite",
					"upload/revert",
					"import/upload",
					"import/interwiki",
					"merge/merge",
					"suppress/block",
					"suppress/reblock",
					"review/approve",
					"review/approve2",
					"review/approve-i",
					"review/approve2-i",
					"review/approve-a",
					"review/approve2-a",
					"review/approve-ia",
					"review/approve2-ia",
					"review/unapprove",
					"review/unapprove2",
					"rights/erevoke",
					"gblblock/gblock",
					"gblblock/gblock2",
					"gblblock/gunblock",
					"gblblock/whitelist",
					"gblblock/dwhitelist",
					"gblblock/modify",
					"globalauth/delete",
					"globalauth/lock",
					"globalauth/unlock",
					"globalauth/hide",
					"globalauth/unhide",
					"globalauth/lockandhid",
					"globalauth/setstatus",
					"suppress/setstatus",
					"gblrights/usergroups",
					"gblrights/groupperms",
					"gblrights/groupprms2",
					"gblrights/groupprms3",
					"suppress/hide-afl",
					"suppress/unhide-afl",
					"articlefeedbackv5/oversight",
					"articlefeedbackv5/unoversight",
					"articlefeedbackv5/hidden",
					"articlefeedbackv5/unhidden",
					"articlefeedbackv5/decline",
					"articlefeedbackv5/request",
					"articlefeedbackv5/unrequest",
					"articlefeedbackv5/flag",
					"articlefeedbackv5/unflag",
					"moodbar/hide",
					"moodbar/restore",
					"moodbar/feedback",
				),
			);
		}

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['prefix'] = array (
				'type' => "string",
			);
		}

		return $paramdesc;
	}


}
