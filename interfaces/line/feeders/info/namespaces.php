<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Namespaces Info-based feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Feeder_Info_Namespaces extends Feeder_Info
{

	public $page_namespaces_only = true;


	# ----- Implemented ----- #

	protected function data_type ()
	{
		return "array/namespace";
	}

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Namespaces";
	}


	protected function info_elements_array()
	{
		$namespaces = $this->core->info->namespaces();
		if ( $this->page_namespaces_only )
			foreach ( $namespaces as $id => $namespace )
				if ( $id < 0 )
					unset ( $namespaces[$id] );
		return $namespaces;
	}


}
