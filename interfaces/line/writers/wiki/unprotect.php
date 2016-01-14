<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Unprotect page Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_protect.php' );


class Writer_Wiki_Unprotect extends Writer_Wiki_GenericProtect
{

	# ----- Instantiated ----- #

	protected function signal_log_slot_name ()
	{
		return "Unprotect";
	}


	# ----- Overriding ----- #

	protected function process_data ( &$signal )
	{
		$this->protections = array (
			'edit'     => "*",
			'move'     => "autoconfirmed",
			'rollback' => "autoconfirmed",
			'delete'   => "sysop",
			'restore'  => "sysop",
		);

		return parent::process_data ( $signal );
	}


}
