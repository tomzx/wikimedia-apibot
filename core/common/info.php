<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Core: Info.
#
#  Backend-independent info access.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../core.php' );



class Info
{

	protected $core;

	public $backends_order;


	# ----- Constructor ----- #

	function __construct ( &$core )
	{
		$this->core = $core;
		$this->backends_order = $this->backends_order();
	}


	# ----- Tools ----- #

	public function log ( $message, $loglevel = LL_INFO, $preface = "info: " )
	{
		$this->core->log ( $message, $loglevel, $preface );
	}


	protected function backends_order ()
	{
		$order = $this->core->settings->get ( 'info', 'backends_order' );

		if ( ( $order === false ) || is_null ( $order ) || empty ( $order ) )
			$order = array ( 'API', 'Web' );

		return $order;
	}


	# ----- Magic ----- #

	public function __call ( $method, $arguments )
	{
		foreach ( $this->backends_order as $backend_name )
		{
			if ( isset ( $this->core->backends[$backend_name] ) &&
				method_exists ( $this->core->backends[$backend_name]->info, $method ) )
			{
				$result = call_user_func_array (
					array ( $this->core->backends[$backend_name]->info, $method ),
					$arguments
				);

				if ( ! is_null ( $result ) )
					return $result;
			}

		}

		return NULL;
	}


}
