<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Properties: Contributors.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Params_Property_Contributors extends API_Params_Property
{

	# ----- Implemented ----- #

	public function modulename ()
	{
		return "contributors";
	}

	protected function hardcoded_paramdesc ( $mwverno )
	{
		if ( ! $this->settings['lax_mode'] && $mwverno && ( $mwverno < 12300 ) )
			return NULL;

		$paramdesc = array (
			'prefix' => "pc",
			'generator' => false,
			'params' => array (
				'group' => array (
					'type' => "string",
					'multi' => true,
					'limit' => 50,
				),
				'excludegroup' => array (
					'type' => "string",
					'multi' => true,
					'limit' => 50,
				),
				'rights' => array (
					'type' => "string",
					'multi' => true,
					'limit' => 50,
				),
				'excluderights' => array (
					'type' => "string",
					'multi' => true,
					'limit' => 50,
				),
				'limit' => array (
					'type' => "limit",
					'max' => 500,
					'default' => 10,
				),
				'continue' => array (
					'type' => "string",
				),
			),
		);

		return $paramdesc;
	}


	public function set_param ( $name, $value )
	{
		if ( ( $name == "group" ) && isset ( $this->params['excludegroup'] ) )
			unset ( $this->params['excludegroup'] );

		if ( ( $name == "excludegroup" ) && isset ( $this->params['group'] ) )
			unset ( $this->params['group'] );

		if ( ( $name == "rights" ) && isset ( $this->params['excluderights'] ) )
			unset ( $this->params['excluderights'] );

		if ( ( $name == "excluderights" ) && isset ( $this->params['rights'] ) )
			unset ( $this->params['rights'] );

		return parent::set_param ( $name, $value );
	}


}
