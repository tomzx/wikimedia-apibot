<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Lists: Categorymembers.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_List_Categorymembers extends API_Params_Query_List
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "categorymembers";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 10900 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "cm",
			'generator' => true,
			'params' => array (
				'category' => array (
					'type' => "string",
				),
				'prop' => array (
					'type' => array (
						"ids",
						"title",
						"sortkey",
						"timestamp",
					),
					'multi' => true,
					'default' => "ids|title",
					'limit' => 50,
				),
				'namespace' => array (
					'type' => "namespace",
					'multi' => true,
					'limit' => 50,
				),
				'sort' => array (
					'type' => array (
						"sortkey",
						"timestamp",
					),
					'default' => "sortkey",
				),
				'continue' => array (
					'type' => "string",
				),
				'limit' => array (
					'type' => "limit",
					'max' => 500,
					'default' => 10,
				),
			),
		);

		if ( $mwverno >= 11200 )
		{
			unset ( $paramdesc['params']['category'] ); // was deprecated and removed

			$paramdesc['params']['title'] = array (
				'type' => "string",
				'required' => true,
			);
			$paramdesc['params']['dir'] = array (
				'type' => array (
					"asc",
					"desc",
				),
				'default' => "asc",
			);
			$paramdesc['params']['start'] = array (
				'type' => "timestamp",
			);
			$paramdesc['params']['end'] = array (
				'type' => "timestamp",
			);
		}

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['startsortkey'] = array (
				'type' => "string",
			);
			$paramdesc['params']['endsortkey'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['sortkeyprefix'] = array (
				'type' => "string",
			);
			$paramdesc['params']['type'] = array (
				'type' => array (
					"page",
					"subcat",
					"file",
				),
				'multi' => true,
				'default' => "page|subcat|file",
				'limit' => 50,
			);
		}

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['startsortkeyprefix'] = array (
				'type' => "string",
			);
			$paramdesc['params']['endsortkeyprefix'] = array (
				'type' => "string",
			);
			$paramdesc['params']['pageid'] = array (
				'type' => "integer",
			);
		}

		return $paramdesc;
	}


	# ----- Overriding ----- #

	public function set_param ( $name, $value )
	{
		if ( $name == 'title' )
		{
			$parts = $this->info->title_parts ( $value );
			if ( empty ( $parts['namespace'] ) ||
				( $this->info->namespace_id ( $parts['namespace'] ) != 14 ) )
				$value = $this->info->namespace_name ( 14 ) . ':' . $parts['name'];
		}

		if ( isset ( $this->params['sort'] ) &&
			( $this->params['sort'] == "timestamp" ) &&
			( ( $name == "startsortkey" ) || ( $name == "endsortkey" ) ||
				( $name == "sortkeyprefix" ) || ( $name == "startsortkeyprefix" ) ||
				( $name == "endsortkeyprefix" ) ) )
			return false;

		if ( ( ! isset ( $this->params['sort'] ) ||
			( $this->params['sort'] != "timestamp" ) ) &&
			( ( $name == "start" ) || ( $name == "end" ) ) )
			return false;

		if ( ( $name == "sort" ) && ( $value == "sortkey" ) &&
			( isset ( $this->params['start'] ) ||
				isset ( $this->params['end'] ) ) )
			return false;

		if ( ( $name == "sort" ) && ( $value == "timestamp" ) &&
			( isset ( $this->params['startsortkey'] ) ||
				isset ( $this->params['endsortkey'] ) ||
				isset ( $this->params['sortkeyprefix'] ) ||
				isset ( $this->params['startsortkeyprefix'] ) ||
				isset ( $this->params['endsortkeyprefix'] ) ) )
			return false;

		return parent::set_param ( $name, $value );
	}


}
