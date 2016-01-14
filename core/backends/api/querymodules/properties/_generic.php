<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Properties: Generic.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_params.php' );



abstract class API_Params_Property extends API_Params_Querymodule
{

	# ----- Overriding ----- #

	public function set_param ( $name, $value )
	{
		if ( ( $name == "prop" ) && ( $value == "all" ) )
		{
			return $his->set_prop_all ( $name );
		}
		else
			return parent::set_param ( $name, $value );
	}


	# ----- New ----- #

	// override in descendants to avoid setting conflicting props
	protected function set_prop_all ( $name )
	{
		$paramdesc = $this->paramdesc();
		return $this->set_param ( $name, $paramdesc['params']['prop']['type'] );
	}


}
