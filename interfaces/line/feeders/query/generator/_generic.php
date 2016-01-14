<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Query Generator generic feeder class
#
#  (Implements also property feeding. If constructed with a property name,
#  this property will be feeded instead of the pages generated / passed.
#  The property is auto-set; set manually its params that might be needed.)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Feeder_Query_Generator extends Feeder_Query
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() .
			".Generator." . ucfirst ( $this->queryname() );
	}


	protected function data_type ()
	{
		return "array/page";
	}


	# ----- Overriding ----- #

	protected function get_query_params ()
	{
		$params = parent::get_query_params();

		$properties = $this->core->info->available_properties();
		foreach ( $properties as $property )
			if ( isset ( $this->$property ) )
				$params[$property] = $this->$property;

		return $params;
	}

	protected function set_query_params ( $params )
	{
		$properties = $this->core->info->available_properties();
		foreach ( $properties as $property )
			if ( isset ( $params[$property] ) )
				$this->$property = $params[$property];

		parent::set_query_params ( $params );
	}


}
