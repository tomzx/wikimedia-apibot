<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Filters: Feed: Lets through the first $count data signals only
#    (if $count is negative - all but the first $count data signals).
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../_checks/simple_callback.php' );


class Filter_Feed_Head extends Filter_Feed
{


	public $count;

	private $counter;


	# ----- Overriding ----- #


	protected function process_start ( &$signal )
	{
		$this->counter = 0;
		return parent::process_start ( $signal );
	}


	protected function slotname_preface ()
	{
		return parent::slotname_preface() . ".Head";
	}


	# ----- Implementing ----- #


	protected function checker ( $checker_params = NULL )
	{
		return new Checker_WithSimpleCallback ( $checker_params,
			array ( $this, "checker_callback" ) );
	}


	protected function element_id_string ( &$signal )
	{
		return $signal->data_unique_id ( $this->default_data_key );
	}


	# ----- New ----- #


	public function checker_callback ( $element )
	{
		if ( isset ( $this->count ) )
			$this->checker->params = $this->count;

		if ( $this->count > 0 )
		{
			$this->counter++;
			return ! ( $this->counter > $this->checker->params );
		}
		else
		{
			$this->counter--;
			return ! ( $this->counter < $this->checker->params );
		}

	}


}
