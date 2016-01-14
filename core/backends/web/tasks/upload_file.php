<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Task: Upload File.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_upload.php' );



class Web_Task_UploadFile extends Web_Task_GenericUpload
{

	# ----- Task support ----- #

	protected function check_result ( $data, $logbeg, $actdesc )
	{
		if ( preg_match ( '/\<div class\=\"error\"\>(.+)\<\/div\>/u', $data, $matches ) )
		{
			$error_text = preg_replace ( '/\<[^\>]+\>/u', "", $matches[1] );
			$this->log ( "Error: Wiki said: " . $error_text, LL_ERROR );
			return false;
		}
		else
		{
			return true;
		}
	}


	# ----- Entry point ----- #

	public function nohooks__go ( $hook_object, $params )
	{
		if ( ( $params['filename]' = $this->resolve_filename ( $params ) )
			=== NULL )
			return false;

		if ( $this->simulation ( "Would upload file '" . $params['filename'] . "'",
			$params ) )
			return true;

		$logbeg = "File " . $params['filename'];
		$actiondesc = "uploaded";

		return $this->act_and_log ( $logbeg, $actiondesc, $params );
	}


}
