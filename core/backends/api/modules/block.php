<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Block.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Module_Block extends API_Module
{

	protected $mustbeposted = true;


	# ----- Implemented ----- #

	public function modulename ()
	{
		return "block";
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
				'user' => array (
					'type' => "string",
					'required' => true,
				),
				'token' => array (
					'type' => "string",
				),
				'gettoken' => array (
					'type' => "boolean",
					'default' => false,
				),
				'expiry' => array (
					'type' => "string",
					'default' => "never",
				),
				'reason' => array (
					'type' => "string",
				),
				'anononly' => array (
					'type' => "boolean",
					'default' => false,
				),
				'autoblock' => array (
					'type' => "boolean",
					'default' => false,
				),
				'nocreate' => array (
					'type' => "boolean",
					'default' => false,
				),
				'noemail' => array (
					'type' => "boolean",
					'default' => false,
				),
				'hidename' => array (
					'type' => "boolean",
					'default' => false,
				),
			),
		);

		if ( $mwverno >= 11400 )
		{
			$paramdesc['params']['allowusertalk'] = array (
				'type' => "boolean",
				'default' => false,
			);
			$paramdesc['params']['reblock'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		if ( $mwverno >= 11800 )
		{
			$paramdesc['params']['watchuser'] = array (
				'type' => "boolean",
				'default' => false,
			);
		}

		return $paramdesc;
	}


}
