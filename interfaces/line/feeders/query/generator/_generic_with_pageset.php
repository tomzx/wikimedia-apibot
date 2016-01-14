<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Query Generator with Pageset generic feeder class
#
#  (Implements also property feeding. If constructed with a property name,
#  this property will be feeded instead of the pages generated / passed.
#  The property is auto-set; set manually its params that might be needed.)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



abstract class Feeder_Query_Generator_WithPageset extends Feeder_Query_Generator
{


	public $set_page_from_signal_data = true;


	# ----- Overriding ----- #


	protected function set_slot_params_from_signal ( &$signal )
	{
		if ( ( $this->set_page_from_signal_data ) &&
			( $signal instanceof LineSignal_Data ) )
		{
			$title = $signal->data_title ( $this->default_data_key );
			if ( ! is_null ( $title ) )
				$this->titles = $title;
			else
			{
				$pageid = $signal->data_pageid ( $this->default_data_key );
				if ( ! is_null ( $pageid ) )
					$this->pageids = $pageid;
			}
		}

		return parent::set_slot_params_from_signal ( $signal );
	}


	# ----- Overriding ----- #

	protected function get_query_params ()
	{
		$params = parent::get_query_params();

		$paramnames = $this->core->info->param_anymodule_parameters_names (
			'pagesetmodule' );
		foreach ( $paramnames as $paramname )
			if ( isset ( $this->$paramname ) )
				$params[$paramname] = $this->$paramname;

		return $params;
	}

	protected function set_query_params ( $params )
	{
		$paramnames = $this->core->info->param_anymodule_parameters_names (
			'pagesetmodule' );
		foreach ( $paramnames as $paramname )
			if ( isset ( $params[$paramname] ) )
				$this->$paramname = $params[$paramname];


		parent::set_query_params ( $params );
	}


}
