<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Fetchers: Wiki: File body fetcher.
#    (Converts input data into a File dataobject.)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Fetcher_Wiki_Filename_Body extends Fetcher_Wiki
{


	public $body_file;   // if you want file body written, set filename here


	# ----- Implemented ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".FileBody";
	}


	protected function process_data ( &$signal )
	{
		if ( ! parent::process_data ( $signal ) )
			return false;

		$file = $signal->data_element ( $this->default_data_key );

		if ( is_object ( $file ) && ( $file instanceof Dataobject ) )
			$element = $file->data();

		elseif ( is_string ( $file ) )
			$element = array ( 'url' => $file, 'title' => basename ( $file ) );

		else
		{
			$this->log ( "Could not determine the URL to fetch the file body from!",
				LL_ERROR );
			return false;
		}

		if ( isset ( $this->body_file ) )
			if ( $this->body_file === "" )
				$this->core->browser->write_into_file = sha1 ( $element['title'] );
			else
				$this->core->browser->write_into_file = $this->body_file;

		$browser_max_retries = $this->core->browser->max_retries;
		$this->core->browser->max_retries = 50;

		try {
			$result = $this->core->browser->xfer ( $element['url'] );
		} catch ( Exception $e ) {
			$result = false;
		}

		if ( $result )
		{
			if ( isset ( $this->body_file ) )
			{
				$element['body_link'] = $this->core->browser->write_into_file;
				$element['body_link_is_tempfile'] = true;
				$filesize = filesize ( $element['body_link'] );
			}
			else
			{
				$element['body'] = $this->core->browser->content;
				$filesize = strlen ( $element['body'] );
			}

			$this->log ( "Fetched also file body ($filesize bytes)", LL_DEBUG );
		}
		else
		{
			$this->log ( "Tried, but could not fetch the file body!", LL_WARNING );
		}

		$this->core->browser->max_retries = $browser_max_retries;

		unset ( $this->core->browser->write_into_file );

		$file = new File ( $this->core, $element );

		$result = $this->set_fetched_data ( $signal, $file );

		$this->set_jobdata ( $result );

		return $result;
	}


	protected function element_typemark ()
	{
		return "file";
	}


}
