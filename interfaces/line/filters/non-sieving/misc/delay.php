<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Filters: Delay a given number of tenths of a second.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



class Filter_Delay extends Filter_NonSieving
{


	public $delay;


	# ----- Constructor ----- #

	function __construct ( $core, $delay = 0 )
	{
		parent::__construct ( $core );
		$this->delay = $delay;
	}


	# ----- Overriding ----- #


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		if ( $result )
			sleep ( $this->delay );

		return $result;
	}


	# ----- Implemented ----- #


	protected function signal_log_slot_name ()
	{
		return "Delay";
	}


	protected function job_params ()
	{
		return array ( 'delay' => $this->delay );
	}


}
