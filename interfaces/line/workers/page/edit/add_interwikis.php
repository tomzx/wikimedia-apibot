<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Adding interwikis Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Worker_EditPage_AddInterwikis extends Worker_EditPage
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Add.Interwikis";
	}


	protected function make_edits ( &$data_block )
	{
		$count = 0;

		if ( isset ( $this->tasks['wiki'] ) )

			if ( $data_block['*']->add_interwiki ( $this->tasks ) )
				$count = 1;

		else

			$count = $data_block['*']->add_interwikis ( $this->tasks );

		$this->add_changes ( '$1 interwiki(s) added', $count );

		return true;
	}


}
