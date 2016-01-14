<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Page namespaces generic filter class
#
#  Does not use the checker object extension!
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );


abstract class Filter_Page_Namespace extends Filter_Page
{

	# ----- Constructor ----- #

	function __construct ( $core, $checker_params = NULL )
	{
		if ( is_string ( $checker_params ) )
			$checker_params = array ( $checker_params );

		foreach ( $checker_params as $key => $param )
			if ( ! is_numeric ( $param ) ) {
				$param = $core->info->namespace_id_by_name ( $param );
				if ( is_null ( $param ) )
					$core->log ( "Bad namespace (" . $param . ") given to " .
						get_class ( $this ), LL_ERROR );
				else
					$checker_params[$key] = $param;
			}

		$this->data_property = "ns";
		parent::__construct ( $core, $checker_params );
	}


	# ----- Instantiating ----- #

	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".Namespace";
	}


}
