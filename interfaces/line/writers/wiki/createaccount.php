<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Create account Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Writer_Wiki_Createaccount extends Writer_Wiki_Generic
{

	public $name         = NULL;
	public $password     = NULL;
	public $domain       = NULL;
	public $email        = NULL;
	public $realname     = NULL;
	public $mailpassword = NULL;
	public $reason       = NULL;
	public $language     = NULL;


	# ----- Instantiated ----- #


	protected function signal_log_slot_name ()
	{
		return "Createaccount";
	}


	protected function task_paramnames ()
	{
		return array_merge (
			parent::task_paramnames(),
			array (
				"name",
				"password",
				"domain",
				"email",
				"realname",
				"mailpassword",
				"reason",
				"language",
			)
		);
	}


	# ----- Overridden ----- #


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$params = $this->get_task_params();

		require_once ( dirname ( __FILE__ ) .
			'/../../../../core/tasks//createaccount.php' );

		$task = new Task_Createaccount ( $this->core );
		$result = $task->go ( $params );

		$this->set_jobdata ( $result );

		return $result;
	}


}
