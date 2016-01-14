<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Meta: Userinfo.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Meta_Userinfo extends API_Params_Query_Meta
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "userinfo";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11100 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "ui",
			'params' => array (
				'prop' => array (
					'type' => array (
						"blockinfo",
						"hasmsg",
						"groups",
						"rights",
						"changeablegroups",
						"options",
						"editcount",
						"ratelimits",
					),
				),
			),
		);

		if ( $mwverno >= 11500 )
		{
			$paramdesc['params']['prop']['type'][] = "email";
		}

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['prop']['type'][] = "realname";
		}

		return $paramdesc;
	}


}
