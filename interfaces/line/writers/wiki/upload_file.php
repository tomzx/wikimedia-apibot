<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Upload file Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_upload.php' );


class Writer_Wiki_UploadFile extends Writer_Wiki_GenericUpload
{

	public $comment   = NULL;
	public $file_body = NULL;
	public $file      = NULL;  // filename to read body from; not used if file_body is set.


	# ----- Instantiated ----- #


	protected function task_paramnames ()
	{
		return array_merge (
			parent::task_paramnames(),
			array (
				"comment",
				"file_body",
				"file",
			)
		);
	}


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".File";
	}


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		if ( empty ( $this->file_body ) )
			if ( ! empty ( $this->file ) && is_readable ( $this->file ) )
				$this->file_body = file_get_contents ( $this->file );

		$params = $this->get_task_params();

		$params['title'] = $signal->data_title ( $this->default_data_key );
		if ( is_null ( $params['title'] ) )
			return false;

		require_once ( dirname ( __FILE__ ) .
			'/../../../../core/tasks//upload_file.php' );

		$task = new Task_UploadFile ( $this->core );
		$result = $task->go ( $params );

		$this->set_jobdata ( $result, array ( 'title' => $params['title'] ) );

		return $result;
	}


}
