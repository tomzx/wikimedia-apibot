<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Expiry times diapazone checker class
#  (Works with any form of time convertable by strtotime().)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/diap_times.php' );


class Checker_Diap_Times_Expiry extends Checker_Diap_Times
{


	# ----- Constructor ----- #


	function __construct ( $diap )
	{
		$diap['min'] = $this->expiry_to_timestamp ( $diap['min'] );
		$diap['max'] = $this->expiry_to_timestamp ( $diap['max'] );
		parent::__construct ( $diap );
	}


	# ----- Tools ----- #


	private function expiry_to_timestamp ( $expiry )
	{
		if ( ( $expiry == "infinity" ) ||
			( $expiry == "infinite" ) ||
			( $expriry == "never" ) )
			return PHP_INT_MAX;
		elseif ( is_numeric ( $expiry ) )
			return $expiry;
		else
			return strtotime ( $expiry );
	}


	# ----- Instantiating ----- #


	public function check ( $element )
	{
		return parent::check ( $this->expiry_to_timestamp ( $element ) );
	}


	public function job_name ()
	{
		return parent::job_name() . ".Expiry";
	}


}
