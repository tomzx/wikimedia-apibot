<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Delete categories sortkeys Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Worker_EditPage_DeleteCategoriesSortkeys extends Worker_EditPage
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Delete.Categories.Sortkeys";
	}


	protected function make_edits ( &$data_block )
	{
		$count = 0;

		if ( is_array ( $this->tasks ) )

			foreach ( $this->tasks as $category )
				$count += $data_block['*']->delete_category_sortkey ( $category );

		else

			$count = $data_block['*']->delete_category_sortkey ( $this->tasks );

		if ( $count )
			$this->add_changes ( '$1 category sortkey(s) deleted', $count );

		return true;
	}


}
