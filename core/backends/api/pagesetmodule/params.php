<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Pagesetmodule: Params.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_params.php' );


class API_Params_Pageset extends API_Params
{


	# ----- Implemented ----- #

	public function rootmodulename ()
	{
		return NULL;
	}

	public function modulename ()
	{
		return "pagesetmodule";
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		$paramdesc = array (
			'prefix' => "",
			'params' => array (
				'titles' => array (
					'type' => "string",
					'multi' => true,
					'limit' => 50,
				),
				'pageids' => array (
					'type' => "integer",
					'multi' => true,
					'limit' => 50,
				),
				'revids' => array (
					'type' => "integer",
					'multi' => true,
					'limit' => 50,
				),
			),
		);

		return $paramdesc;
	}


	# ----- Overriding ----- #

	# MW ver < 1.15 dont describe pagesetmodule, but the bot MUST have a desc of it
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
			return $this->hardcoded_paramdesc ( 0 );
		}
	}


}
