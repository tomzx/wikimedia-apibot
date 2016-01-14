<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Generic.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../../_generic/task.php' );



abstract class API_Task extends Backend_Task
{

	# ----- Basic tools ----- #

	protected function action ()
	{
		$action = $this->api_action();
		if ( empty ( $action ) )
			return NULL;

		$name = $action->modulename();
		if ( ! $this->backend->info->is_available_action ( $name ) )
			$this->log ( 'I see no info that wiki supports action "' . $name . '"',
				LL_WARNING, $this->logpreface );

		$rights = $this->action_rights();
		foreach ( $rights as $right )
			if ( ! $this->backend->info->have_i_permission ( $right ) )
				$this->log ( 'For action "' . $name . '" I need the right "' . $right .
					'", and I see no info for having it', LL_WARNING, $this->logpreface );

		return $action;
	}


	# ----- Abstract ----- #


	abstract protected function api_action ();


}
