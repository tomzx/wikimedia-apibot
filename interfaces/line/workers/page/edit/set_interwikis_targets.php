<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Replacing interwikis targets Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Worker_EditPage_SetInterwikisTargets extends Worker_EditPage
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Set.Interwikis.Targets";
	}


	protected function make_edits ( &$data_block )
	{
		$count = 0;

		if ( isset ( $this->tasks['wiki'] ) )

			$count = $data_block['*']->set_interwiki_target ( $this->tasks['wiki'],
				$this->tasks['target'] );

		else

			foreach ( $this->tasks as $interwiki )

				$count += $data_block['*']->set_interwiki_target ( $interwiki['wiki'],
					$interwiki['target'] );

		$this->add_changes ( '$1 interwiki target(s) set', $count );

		return true;
	}


}
