<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Adding interwikis strings Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Worker_EditPage_AddInterwikisStrings extends Worker_EditPage
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Add.InterwikisStrings";
	}


	protected function make_edits ( &$data_block )
	{
		$count = 0;

		if ( is_array ( $this->tasks ) )

			foreach ( $this->tasks as $interwiki_string )
				if ( $data_block['*']->add_interwiki_string ( $interwiki_string ) )
					$count += 1;

		else

			if ( $data_block['*']->add_interwiki_string ( $this->tasks ) )
				$count = 1;

		$this->add_changes ( '$1 interwiki string(s) added', $count );

		return true;
	}


}
