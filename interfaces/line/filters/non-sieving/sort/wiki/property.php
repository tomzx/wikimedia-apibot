<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Filters: Sort passing data: By size attribute.
#
#  WARNING: Can hog a LOT of memory - be careful what feed you run it over!
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



class Filter_Sort_Property extends Filter_Sort
{

	public $property;


	# ----- Constructor ----- #

	function __construct ( $core, $property = NULL )
	{
		parent::__construct ( $core );
		$this->property = $property;
	}


	# ----- Overriding ----- #


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'property' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'property' );

		return parent::set_params ( $params );
	}


	# ----- Overridable ----- #

	protected function sortkey ( &$signal )
	{
		if ( ! isset ( $this->property ) || is_null ( $this->property ) )
		{
			$this->log ( "Filter " . $this->signal_log_slot_name() .
				": Property not specified - exitting", LL_ERROR );
			return NULL;
		}

		return $signal->data_element_property ( $this->default_data_key,
			$this->property );
	}


}
