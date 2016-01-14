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



class API_Task_UploadFile extends API_Task_GenericUpload
{


	# ----- Overriding ----- #


	protected function action_rights ()
	{
		return array ( "upload" );
	}


	# ----- Entry point ----- #


	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['filename'] = $this->resolve_file_name ( $params['filename'] ) )
			=== NULL )
			return false;

		if ( $this->simulation ( "Would upload file " .
			$params['filename'], $params ) )
			return true;

		$logbeg = "File " . $params['filename'];
		$actiondesc = "uploaded";

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


}
