<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Pages (by titles) batch fetcher class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../../../core/data/page.php' );
require_once ( dirname ( __FILE__ ) .
	'/../../../../core/queries/pageset/titles.php' );



abstract class Fetcher_Wiki_Batch extends Fetcher_Wiki
{


	public $properties;

	public $batch_size;


	protected $titles = array();


	# ----- Destructor ----- #


	function __destruct ()
	{
		if ( count ( $this->titles ) > 0 )
		{
			$this->log ( "ATTENTION! Closing with data still unfetched:", LL_ERROR );
			foreach ( $this->titles as $title )
				$this->log ( "  [[" . $title . "]]", LL_ERROR );
		}
	}


	# ----- Overriding ----- #


	protected function propagate_signal ( &$signal )
	{
		if ( $signal instanceof LineSignal_Data )  // data signals are recreated - don't propagate them
			return true;
		else
			return parent::propagate_signal ( $signal );
	}


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Batch";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'properties' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'properties' );

		return parent::set_params ( $params );
	}


	protected function process_data ( &$signal )
	{
		if ( ! parent::process_data ( $signal ) )
			return false;

		if ( ! isset ( $this->batch_size ) )
			$this->batch_size = $this->core->info->pagesetmodule_available_limit (
				$this->is_content_in_properties() );

		$title = $signal->data_title ( $this->default_data_key );
		if ( in_array ( $title, $this->titles ) )
		{
			$this->log ( "Skipped [[$title]] from batch fetching - already queued",
				LL_DEBUG );
		}
		else
		{
			$this->titles[] = $title;
			$this->log ( "Queued [[$title]] for batch fetching (" .
				count ( $this->titles ) . " so far)", LL_DEBUG );
		}

		if ( count ( $this->titles ) >= $this->batch_size )
		{
			$result = $this->fetch_and_propagate_batch ();
			$this->titles = array();
		}
		else
		{
			$result = true;
		}

		$this->set_jobdata ( $result );

		return $result;
	}


	protected function process_end ( &$signal )
	{
		$this->fetch_and_propagate_batch();
		return parent::process_end ( $signal );
	}


	# ----- Implemented ----- #


	protected function element_typemark () {}  // not used here and in descendants


	# ----- New ----- #


	protected function fetch_and_propagate_batch ()
	{
		if ( count ( $this->titles ) > 0 )
		{

			$this->log ( "Fetching batch (" . count ( $this->titles ) . " titles)...",
				LL_DEBUG );

			$params = array();

			$params['_pageset'] = array (
				'titles' => $this->titles,
				'limit' => "max",
			);

			if ( isset ( $this->properties ) && is_array ( $this->properties ) )
				$params['_prop'] = $this->properties;

			$query = new Query_Pageset_Titles ( $this->core );
			$query->set_params ( $params );

			$result = true;

			$counter = 0;
			while ( $element = $query->element() )
			{
				if ( $element === NULL )
				{
					$this->log ( "Could not fetch a batch", LL_ERROR );
					$result = false;
					break;
				}

				$counter++;

				if ( isset ( $element['invalid'] ) )
				{

					$this->log ( "[[" . $element['title'] . "]] is invalid - skipping it",
						LL_INFO );

				}
				else
				{

					$this->log ( "Fetched (from batch) [[" . $element['title'] .
						"]] (" . $counter . " of " . count ( $this->titles ) . ")" );

					$signal = $this->element_to_signal ( $element );

					parent::propagate_signal ( $signal );

				}
			}

		}
		else
		{
			$result = true;
		}

		$this->titles = array();

		return $result;
	}


	private function is_content_in_properties ()
	{
		if ( isset ( $this->properties['revisions']['prop'] ) &&
			is_array ( 'content', $this->properties['revisions']['prop'] ) &&
			in_array ( 'content', $this->properties['revisions']['prop'] ) )

			return true;

		return false;
	}


	# ----- Abstract ----- #


	abstract protected function element_to_signal ( $element );


}
