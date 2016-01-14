<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Protect.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Module_Protect extends API_Module
{

	protected $mustbeposted = true;


	# ----- Implemented ----- #

	public function modulename ()
	{
		return "protect";
	}


	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 11200 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "",
			'readrights' => true,
			'writerights' => true,
			'mustbeposted' => true,
			'params' => array (
				'title' => array (
					'type' => "string",
				),
				'token' => array (
					'type' => "string",
				),
				'protections' => array (
					'type' => "string",
					'required' => true,
					'multi' => true,
					'limit' => 50,
				),
				'expiry' => array (
					'type' => "string",
					'multi' => true,
					'allowsduplicates' => true,
					'default' => "infinite",
					'limit' => 50,
				),
				'reason' => array (
					'type' => "string",
				),
				'cascade' => array (
					'type' => "boolean",
					'default' => false,
				),
			),
		);

		if ( $mwverno >= 11500 )
		{
			$paramdesc['params']['watch'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		if ( $mwverno >= 11700 )
		{
			$paramdesc['params']['watchlist'] = array (
				'type' => array (
					"watch",
					"unwatch",
					"preferences",
					"nochange",
				),
				'default' => "preferences",
			);

			$paramdesc['params']['watch']['deprecated'] = true;
		}

		return $paramdesc;
	}


	# ----- Overriding ----- #


	public function nohooks__set_param ( $hook_object, $name, $value )
	{
		if ( is_array ( $value ) )
		{
			$protequals = array();

			foreach ( $value as $protection => $level )
				if ( is_numeric ( $protection ) )
					$protequals[] = $level;
				else
					$protequals[] = $protection . "=" . $level;

			$value = implode ( '|', $protequals );
		}

		return parent::nohooks__set_param ( $hook_object, $name, $value );
	}


}
