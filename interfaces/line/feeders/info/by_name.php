<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Specified module Info-based feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Feeder_Info_ByName extends Feeder_Info
{

	protected $infotype;
	protected $infoname;

	protected $data_type;

	# ----- Constructor ----- #

	function __construct ( $core, $infotype, $infoname, $data_type = "" )
	{
		$this->infotype = $infotype;
		$this->infoname = $infoname;
		$this->data_type = $data_type;
		parent::__construct ( $core );
	}


	# ----- Implemented ----- #

	protected function data_type ()
	{
		return $this->data_type;
	}

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() .
			"." . $this->infotype . "." . $this->infoname;
	}


	protected function info_elements_array()
	{
		return $this->core->info->infotype_element (
			$this->infotype, $this->infoname );
	}


}
