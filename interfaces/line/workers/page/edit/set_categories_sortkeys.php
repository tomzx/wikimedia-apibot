<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Setting categories sortkeys Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Worker_EditPage_SetCategoriesSortkeys extends Worker_EditPage
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Set.Categories.Sortkeys";
	}


	protected function make_edits ( &$data_block )
	{
		$count = 0;

		if ( isset ( $this->tasks['name'] ) )

			$count = $data_block['*']->set_category_sortkey ( $this->tasks['name'],
				$this->tasks['sortkey'] );

		else

			foreach ( $this->tasks as $category )

				$count += $data_block['*']->set_category_sortkey ( $category['name'],
					$category['sortkey'] );

		$this->add_changes ( '$1 category sortkey(s) set', $count );

		return true;
	}


}
