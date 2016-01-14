<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Namespace Allnames Info-based feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/namespaces.php' );


class Feeder_Info_Namespace_Allnames extends Feeder_Info_Namespaces
{

	public $namespace = 0;  // set to feed only this namespace names and aliases


	# ----- Implemented ----- #

	protected function data_type ()
	{
		return "string/nsname";
	}

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Allnames";
	}


	protected function info_elements_array()
	{
		if ( is_numeric ( $this->core->info->namespace_id ( $this->namespace ) ) )
		{
			return $this->core->info->namespace_allnames ( $this->namespace );
		}
		else
		{
			$this->log ( "Bad namespace (" . $this->namespace .
				") given to a Namespace Allnames feeder", LL_ERROR );
			return NULL;
		}
	}


	# ----- Overriding ----- #

	protected function signal_log_job ()
	{
		$job = parent::signal_log_job();
		$job['params']['namespace'] = $this->namespace;
		return $job;
	}


}
