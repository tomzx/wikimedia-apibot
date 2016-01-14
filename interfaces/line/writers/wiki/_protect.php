<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic Protect page Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


abstract class Writer_Wiki_GenericProtect extends Writer_Wiki_Generic
{

	public $reason      = NULL;

	public $protections = NULL;
	public $expiry      = NULL;
	public $cascade     = NULL;


	# ----- Instantiated ----- #


	protected function task_paramnames ()
	{
		return array_merge (
			parent::task_paramnames(),
			array (
				"reason",
				"protections",
				"expiry",
				"cascade",
			)
		);
	}


	# ----- Overriding ----- #

	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$params = $this->get_params();

		$params['title'] = $signal->data_title ( $this->default_data_key );
		if ( is_null ( $params['title'] ) )
			return false;

		require_once ( dirname ( __FILE__ ) .
			'/../../../../core/tasks//protect.php' );

		$task = new Task_Protect ( $this->core );
		$result = $task->go ( $params );

		$this->set_jobdata ( $result, array ( 'title' => $params['title'] ) );

		return $result;
	}


}
