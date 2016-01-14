<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Querymodules: Generic.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_params.php' );



abstract class API_Params_Querymodule extends API_Params
{

	protected $is_generator;


	# ----- Constructor ----- #

	function __construct ( $hooks, $info, $settings, $is_generator )
	{
		$this->is_generator = $is_generator;
		parent::__construct ( $hooks, $info, $settings );
	}


	# ----- Overriding ----- #

	protected function paramdesc ()
	{
		$paramdesc = parent::paramdesc();

		if ( ! is_null ( $paramdesc ) && $this->is_generator )
			if ( isset ( $paramdesc['generator'] ) )
				$paramdesc['prefix'] = "g" . $paramdesc['prefix'];
			else
				throw new ApibotException_InternalError (
					"Module '" . $this->modulename() . "' cannot be a generator" );

		return $paramdesc;
	}


	# ----- Implemented ----- #

	protected function rootmodulename ()
	{
		return "querymodules";
	}


	# ----- Continues handling ----- #

	public function set_continue_params ( $continues )
	{
		$modulename = $this->modulename();

		if ( isset ( $continues[$modulename] ) &&
			is_array ( $continues[$modulename] ) )
		{
			$prefix = $this->prefix();
			$prefixlen = strlen ( $prefix );
			foreach ( $continues[$modulename] as $param => $value )
				if ( substr ( $param, 0, $prefixlen ) == $prefix )
					return $this->set_param ( substr ( $param, $prefixlen ), $value );
		}

		return false;
	}


	# ----- Other ---- #

	public function can_be_generator ()
	{
		return isset ( $this->paramdesc['generator'] );
	}


}
