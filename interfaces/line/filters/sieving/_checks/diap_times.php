<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Times diapazone checker class
#  (Works with any form of time convertable by strtotime().)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/diap.php' );


class Checker_Diap_Times extends Checker_Diap
{


	# ----- Constructor ----- #


	function __construct ( $diap )
	{
		if ( ! is_numeric ( $diap['min'] ) )
			$diap['min'] = strtotime ( $diap['min'] );
		if ( ( $diap['min'] === false ) || ( $diap['min'] === -1 ) )
			$this->log ( "Bad min timestamp while constructing " .
				$this->signal_log_slot_name(), LL_ERROR );

		if ( ! is_integer ( $diap['max'] ) )
			$diap['max'] = strtotime ( $diap['max'] );
		if ( ( $diap['max'] === false ) || ( $diap['max'] === -1 ) )
			$this->log ( "Bad max timestamp while constructing " .
				$this->signal_log_slot_name(), LL_ERROR );

		parent::__construct ( $diap );
	}


	# ----- Instantiating ----- #


	public function check ( $element )
	{
		if ( ! is_numeric ( $element ) )
			$element = strtotime ( $element );
		if ( ( $element === false ) )
			return NULL;
		return parent::check ( $element );
	}


	public function job_name ()
	{
		return parent::job_name() . ".Times";
	}


}
