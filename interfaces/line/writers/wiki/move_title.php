<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Move page Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_move.php' );


class Writer_Wiki_MoveTitle extends Writer_Wiki_GenericMove
{

	# ----- Instantiated ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Title";
	}


	# ----- Overriding ----- #


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$params = $this->get_task_params();

		$params['from'] = $signal->data_title ( $this->default_data_key );
		if ( is_null ( $params['from'] ) )
			return false;

		if ( empty ( $params['to'] ) )
		{
			if ( empty ( $this->to_title ) )
				$this->to_title = $signal->data_to_title ( $this->default_data_key );
			if ( empty ( $this->to_title ) )
				return NULL;
			else
				$params['to'] = $this->to_title;
		}

		if ( empty ( $this->to_title ) )
		{
			$result = NULL;
		}
		else
		{
			require_once ( dirname ( __FILE__ ) .
				'/../../../../core/tasks//move_title.php' );

			$task = new Task_MoveTitle ( $this->core );
			$result = $task->go ( $params );
		}

		if ( $result )
		{
			$data_element = $signal->data_element ( $this->default_data_key );
			$data_element['title'] = $this->to_title;
			$signal->set_data_element ( $this->default_data_key, $data_element );
		}

		$this->set_jobdata ( $result, array ( 'from' => $params['from'] ) );

		return $result;
	}


}