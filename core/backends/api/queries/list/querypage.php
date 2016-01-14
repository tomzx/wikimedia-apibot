<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: List: Querypage.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class API_Query_List_Querypage extends API_Query_List
{

	# --- Settings --- #

	protected $page;

	# --- Results (in addition to the data) ---

	public $results;


	# ----- Constructor ----- #

	function __construct ( $page, $backend, $settings = array(), $defaults = array() )
	{
		$this->page = $page;
		parent::__construct ( $backend, $settings, $defaults );
	}


	# ----- Overriding ----- #

	protected function results ( $result )
	{
		$results = parent::results ( $result );
		if ( isset ( $results['results'] ) )
		{
			$data = $results['results'];
			unset ( $results['results'] );
			$this->results = $results;

			if ( isset ( $results['cached'] ) )
				if ( isset ( $results['cachedtimestamp'] ) )
					foreach ( $data as &$querypage )
						$querypage['cachedtimestamp'] = $results['cachedtimestamp'];

			return $data;
		}
		else
		{
			unset ( $this->results );
			return $results;
		}
	}


	# ----- Implemented ----- #

	public function queryname ()
	{
		return "querypage";
	}


}
