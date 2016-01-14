<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Upload File.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_upload.php' );



class API_Task_UploadFile_ByURL extends API_Task_GenericUpload
{


	# ----- Overriding ----- #


	protected function act_and_log ( $logbeg, $actdesc,
		$params = array(), $setnames = array() )
	{
		$result = parent::act_and_log ( $logbeg, $actdesc, $params, $setnames );

		if ( is_array ( $result ) && isset ( $result['upload_session_key'] ) )
			return $result['upload_session_key'];
		else
			return $result;
	}


	protected function action_rights ()
	{
		return array ( "upload_by_url" );
	}


	# ----- Entry points ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['filename'] = $this->resolve_file_name ( $params['filename'] ) )
			=== NULL )
			return false;

		if ( empty ( $params['url'] ) )
		{
			$this->log ( "Cannot upload file by URL without the URL", LL_ERROR );
			return false;
		}

		if ( $this->simulation ( "Would upload file " .
			$params['filename'] . " from URL " . $params['url' .
			( $params['asyncdownload'] ? " asynchroneously" : "" ), $params ) )
			return true;

		$logbeg = "File " . $params['filename'] . " URL";
		$actiondesc = "uploaded";

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


	public function upload_url_async ( $params )
	{
		return $this->upload_url (
			array_merge ( $params, array ( 'asyncdownload' => 1 ) )
		);
	}


	public function is_url_uploaded ( $params )
	{
		$logbeg = "File " . $params['filename'] . " upload by URL status";
		$actiondesc = "checked";

		return $this->act_and_log ( $logbeg, $actiondesc,
			array_merge ( $params, array ( 'httpstatus' => 1 ) ) );
	}


}
