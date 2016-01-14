<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Properties: Info.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Property_Info extends API_Params_Property
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "info";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 10900 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "in",
			'params' => array (
			),
		);

		if ( $mwverno >= 11100 )
		{
			$paramdesc['params']['prop'] = array (
				'type' => array (
					"protection",
				),
				'multi' => true,
				'limit' => 50,
			);
			$paramdesc['params']['token'] = array (
				'type' => array (
					"edit",
					"delete",
					"protect",
					"move",
				),
				'multi' => true,
				'limit' => 50,
			);
		}

		if ( $mwverno >= 11300 )
		{
			$paramdesc['params']['prop']['type'][] = "talkid";
			$paramdesc['params']['prop']['type'][] = "subjectid";
			$paramdesc['params']['token']['type'][] = "block";
			$paramdesc['params']['token']['type'][] = "unblock";
		}

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['prop']['type'][] = "url";
			$paramdesc['params']['prop']['type'][] = "readable";
			$paramdesc['params']['token']['type'][] = "email";
		}

		if ( $mwverno >= 11500 )
		{
			$paramdesc['params']['token']['type'][] = "import";

			$paramdesc['params']['continue'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['prop']['type'][] = "watched";
			$paramdesc['params']['prop']['type'][] = "preload";
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['prop']['type'][] = "displaytitle";
		}

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['token']['type'][] = "watch";
		}

		if ( $mwverno >= 12200 )
		{
			$paramdesc['params']['prop']['type'][] = "contentmodel";
			$paramdesc['params']['prop']['type'][] = "pagelanguage";
		}

		return $paramdesc;
	}


}
