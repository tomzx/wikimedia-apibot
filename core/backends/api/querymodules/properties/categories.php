<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Properties: Categories.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Property_Categories extends API_Params_Property
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "categories";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11100 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "cl",
			'generator' => true,
			'params' => array (
				'prop' => array (
					'type' => array (
						"sortkey",
					),
					'multi' => true,
					'limit' => 50,
				),
			),
		);

		if ( $mwverno >= 11300 )
		{
			$paramdesc['params']['prop']['type'][] = "timestamp";

			$paramdesc['params']['limit'] = array (
				'type' => "limit",
				'max' => 500,
				'default' => 10,
			);
			$paramdesc['params']['continue'] = array (
				'type' => "string",
			);
		}

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['show'] = array (
				'type' => array (
					"hidden",
					"!hidden",
				),
				'multi' => true,
				'limit' => 50,
			);
		}

		if ( $mwverno >= 11500 )
		{
			$paramdesc['params']['categories'] = array (
				'type' => "string",
				'multi' => true,
				'limit' => 50,
			);
		}

		if ( $mwverno >= 11600 )
		{
			$paramdesc['params']['prop']['type'][] = "hidden";
		}

		if ( $mwverno >= 11900 )
		{
			$paramdesc['params']['dir'] = array (
				'type' => array (
					"ascending",
					"descending",
				),
				'default' => "ascending",
			);
		}

		return $paramdesc;
	}

	# ----- Overriding ----- #

	public function set_param ( $name, $value )
	{
		if ( ( $name == "categories" ) && ! is_array ( $value ) )
		{
			$namespace = $this->info->title_namespace ( $value );
			if ( empty ( $namespace ) )
			{
				$value = $this->info->namespace_basic_name_by_id ( 14 ) . ':' . $value;
			}
			elseif ( $this->info->namespace_id ( $namespace ) != 14 )
			{
				return false;
			}
		}
		parent::set_param ( $name, $value );
	}


}
