<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Mainmodule: Params.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_params.php' );


class API_Params_Mainmodule extends API_Params
{

	# ----- Constructor ----- #

	function __construct ( $hooks, $info = NULL, $settings = array() )
	{
		parent::__construct ( $hooks, $info, $settings );

		if ( isset ( $this->settings['format'] ) &&
			( $this->settings['format'] == "json" ) &&
			! function_exists ( 'json_decode' ) )
		{
			$this->log ( "JSON exchange format is not supported - will use PHP instead",
				LL_WARNING );
			$this->settings['format'] = "php";
		}
	}


	# ----- Implemented ----- #

	public function modulename ()
	{
		return "mainmodule";
	}

	protected function rootmodulename ()
	{
		return NULL;
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		$paramdesc = array (
			'prefix' => "",
			'params' => array (
				'format' => array (
					'type' => array (
						"json",
						"jsonfm",
						"php",
						"phpfm",
						"wddx",
						"wddxfm",
						"xml",
						"xmlfm",
						"yaml",
						"yamlfm",
						"rawfm",
						"txt",
						"txtfm",
						"dbg",
						"dbgfm",
					),
					'default' => "xmlfm",
				),
				'action' => array (
					'type' => array (
						"block",
						"expandtemplates",
						"help",
						"feedwatchlist",
						"login",
						"logout",
						"move",
						"opensearch",
						"paraminfo",
						"parse",
						"protect",
						"query",
						"rollback",
						"undelete",
						"unblock",
					),
					'default' => "help",
				),
			),
		);

		if ( $mwverno >= 11000 )
		{
			$paramdesc['params']['version'] = array (
				'type' => "boolean",
				'default' => false,
			);
			$paramdesc['params']['maxlag'] = array (
				'type' => "integer",
			);
		}

		if ( $mwverno >= 11200 )
		{
			$paramdesc['params']['action']['type'][] = "delete";
		}

		if ( $mwverno >= 11300 )
		{
			$paramdesc['params']['smaxage'] = array (
				'type' => "integer",
				'default' => 0,
			);
			$paramdesc['params']['maxage'] = array (
				'type' => "integer",
				'default' => 0,
			);
			$paramdesc['params']['action']['type'][] = "edit";
		}

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['requestid'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['servedby'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		return $paramdesc;
	}


	# ----- Overriding ----- #

	# MW ver < 1.15 dont describe mainmodule, but the bot MUST have a desc of it
	protected function paramdesc ()
	{
		if ( is_object ( $this->info ) )
		{
			if ( $this->info->param_info_isset() &&
				$this->info->param_anymodule_exists (
					$this->modulename(), $this->rootmodulename() ) )
			{
				return $this->paraminfo_paramdesc();
			}
			else
			{
				$mwverno = $this->info->wiki_version_number();
				return $this->hardcoded_paramdesc ( $mwverno );
			}
		}
		else
		{
			return $this->hardcoded_paramdesc ( NULL );
		}
	}


}
