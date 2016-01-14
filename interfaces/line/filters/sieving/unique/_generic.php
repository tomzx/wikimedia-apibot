<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Filters: Pass only Unique data.
#
#  Override id_string() to create your own criteria for uniqueness.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../_checks/unique.php' );



abstract class Filter_Unique extends Filter_Sieving
{


	public $flush_on_feed_beg = true;


	# ----- Constructor ----- #


	function __construct ( $core )
	{
		parent::__construct ( $core, NULL );
		$this->checker = $this->checker ( NULL );
	}


	# ----- Overriding ----- #


	protected function process_start ( &$signal )
	{
		$result = parent::process_start ( $signal );
		if ( $this->flush_on_feed_beg )
			$this->checker->reset();
		return $result;
	}


	# ----- Implemented ----- #


	protected function job_params ()
	{
		return array ( 'flush_on_feed_beg' => $this->flush_on_feed_beg );
	}


	protected function checker ( $checker_params, $slotname_preface = NULL,
		$slotname_postfix = NULL )
	{
		return new Checker_Unique ( $checker_params );
	}


	protected function element_id_string ( &$signal )
	{
		return $this->element_to_check ( $signal );
	}


}

