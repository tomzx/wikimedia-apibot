<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Delete regexes Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Worker_EditPage_DeleteRegexes extends Worker_EditPage
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Delete.Regexes";
	}


	protected function make_edits ( &$data_block )
	{
		$count = 0;

		if ( is_array ( $this->items ) )

			foreach ( $this->items as $regex )
				if ( $data_block['*']->delete ( $regex ) )
					$count += 1;

		else
		{
			if ( $data_block['*']->delete ( $this->items ) )
				$count = 1;
		}

		if ( $count )
			$this->add_changes ( '$1 regex(es) deleted', $count );

		return true;
	}


}
