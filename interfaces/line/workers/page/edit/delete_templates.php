<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit page - Delete templates Worker class.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Worker_EditPage_DeleteTemplates extends Worker_EditPage
{

	# ----- Instantiating ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Delete.Templates";
	}


	protected function make_edits ( &$data_block )
	{
		$count = 0;

		if ( isset ( $this->tasks['name'] ) )

			$count = $data_block['*']->delete_templates (
				$this->tasks['name'],
				( isset ( $this->tasks['namespace'] )
					? $this->tasks['namespace']
					: NULL ),
				( isset ( $this->tasks['wiki'] )
					? $this->tasks['wiki']
					: NULL ),
				( isset ( $this->tasks['limit'] )
					? $this->tasks['limit']
					: NULL ) );

		else

			foreach ( $this->tasks as $template )

				$count += $data_block['*']->delete_templates (
					$template['name'],
					( isset ( $template['namespace'] )
						? $template['namespace']
						: NULL ),
					( isset ( $template['wiki'] )
						? $template['wiki']
						: NULL ),
					( isset ( $template['limit'] )
						? $template['limit']
						: NULL ) );

		$this->add_changes ( '$1 template(s) added', $count );

		return true;
	}


}
