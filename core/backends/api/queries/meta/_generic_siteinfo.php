<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Query: Meta: Siteinfo.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


abstract class API_Query_Meta_GenericSiteinfo extends API_Query_Meta
{

	public $prop;


	# ----- Constructor ----- #

	function __construct ( $backend, $settings = array(), $defaults = array() )
	{
		$this->prop = $this->querykey();
		parent::__construct ( $backend, $settings, $defaults );
	}


	# ----- Overriding ----- #

	protected function results ( $result )
	{
		if ( $result )
			if ( isset ( $this->action->data['query'][$this->querykey()] ) )
			{
				$results = array();
				foreach ( $this->action->data['query'][$this->querykey()] as
					$propkey => $property )
					if ( is_numeric ( $propkey ) )
						$results[] = $property;
					else
						$results[$propkey] = $property;
				return $results;
			}
			else
				return NULL;
		else
			return array();
	}


	# ----- Implemented ----- #

	public function queryname ()
	{
		return "siteinfo";
	}


	// Override $this->querykey() in the descendants!

}
