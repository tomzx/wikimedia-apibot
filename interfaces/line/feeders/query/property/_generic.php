<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Query Page property generic feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );



abstract class Feeder_Query_Property extends Feeder_Query
{

	public $parent_page_key = "";

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


	protected function get_query_params ()
	{
		$params = parent::get_query_params();

		$paramnames = $this->core->info->param_anymodule_parameters_names (
			'pagesetmodule' );

		foreach ( $paramnames as $paramname )
			if ( isset ( $this->$paramname ) )
				$params[$paramname] = $this->$paramname;

		if ( isset ( $this->parent_page_key ) )
			$params['parent_page_key'] = $this->parent_page_key;

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


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() .
			".PageProperty." . ucfirst ( $this->queryname() );
	}


}
