<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Properties: Revisions.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Property_Revisions extends API_Params_Property
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "revisions";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 10800 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "rv",
			'params' => array (  // todo! a lot of these are introduced in later versions - track this!
				'prop' => array (
					'type' => array (
						"timestamp",
						"user",
						"comment",
						"content"
					),
					'multi' => true,
					'limit' => 50,
				),
				'limit' => array (
					'type' => "limit",
					'max' => 500,
				),
				'startid' => array (
					'type' => "integer",
				),
				'endid' => array (
					'type' => "integer",
				),
				'start' => array (
					'type' => "timestamp",
				),
				'end' => array (
					'type' => "timestamp",
				),
				'dir' => array (
					'type' => array (
						"older",
						"newer",
					),
				),
			),
		);

		if ( $mwverno >= 11100 )
		{
			$paramdesc['params']['prop']['type'][] = "ids";
			$paramdesc['params']['prop']['type'][] = "flags";
			$paramdesc['params']['prop']['type'][] = "size";

			$paramdesc['params']['user'] = array (
				'type' => "string",
			);
			$paramdesc['params']['excludeuser'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11200 )
		{
			$paramdesc['params']['expandtemplates'] = array (
				'type' => "boolean",
				'default' => false,
			);
			$paramdesc['params']['token'] = array (
				'type' => array (
					"rollback",
				),
				'multi' => true,
				'limit' => 50,
			);
		}

		if ( $mwverno >= 11300 )
		{
			$paramdesc['params']['section'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['generatexml'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		if ( $mwverno >= 11500 )
		{
			$paramdesc['params']['diffto'] = array (
				'type' => "string",
			);
			$paramdesc['params']['continue'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['prop']['type'][] = "parsedcomment";
			$paramdesc['params']['prop']['type'][] = "tags";

			$paramdesc['params']['difftotext'] = array (
				'type' => "string",
			);
			$paramdesc['params']['tag'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['prop']['type'][] = "userid";

			$paramdesc['params']['parse'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		if ( $mwverno >= 11900 )
		{
			$paramdesc['params']['prop']['type'][] = "sha1";
		}

		if ( $mwverno >= 12100 )
		{
			$paramdesc['params']['contentmodel'] = array (
				'type' => "string",
			);
			$paramdesc['params']['prop']['type'][] = "contentformat";
		}

		return $paramdesc;
	}


	# ----- Overriding ----- #

	public function params ()
	{
		$this->set_param_dir ( 'start', 'end', 'dir' );
		$this->set_param_dir ( 'startid', 'endid', 'dir' );

		return parent::params();
	}


	public function set_param ( $name, $value )
	{
		if ( ( $name == "startid" ) && isset ( $this->params['start'] ) )
		{
			unset ( $this->params['start'] );
			unset ( $this->params['end'] );
		}
		if ( ( $name == "start" ) && isset ( $this->params['startid'] ) )
		{
			unset ( $this->params['startid'] );
			unset ( $this->params['endid'] );
		}

		return parent::set_param ( $name, $value );
	}


}
