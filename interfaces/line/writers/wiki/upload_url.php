<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Upload URL Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_upload.php' );


class Writer_Wiki_UploadURL extends Writer_Wiki_GenericUpload
{

	public $comment = NULL;
	public $url     = NULL;

	public $async   = NULL;  // set to 1 for async DL, leave empty for default


	# ----- Instantiated ----- #


	protected function task_paramnames ()
	{
		return array_merge (
			parent::task_paramnames(),
			array (
				"comment",
				"url",
				"async",
			)
		);
	}


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".dURL";
	}


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$params = $this->get_task_params();

		$params['title'] = $signal->data_title ( $this->default_data_key );
		if ( is_null ( $params['title'] ) )
			return false;

		require_once ( dirname ( __FILE__ ) .
			'/../../../../core/tasks//upload_url.php' );

		$task = new Task_UploadURL ( $this->core );
		$result = $task->go ( $params );

		$this->set_jobdata ( $result, array ( 'title' => $params['title'] ) );

		return $result;
	}


}
