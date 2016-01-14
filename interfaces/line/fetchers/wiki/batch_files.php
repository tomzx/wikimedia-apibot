<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Fetcher: Wiki: Files (by page title or filename) batch
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_batch.php' );
require_once ( dirname ( __FILE__ ) . '/../../../../core/data/file.php' );


class Fetcher_Wiki_Batch_Files extends Fetcher_Wiki_Batch
{


	public $fetch_body = false;

	public $body_file;


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Files";
	}


	protected function process_data ( &$signal )
	{
		return parent::process_data ( $signal );
	}


	protected function fetch_and_propagate_batch ()
	{
		foreach ( $this->titles as &$title )
		{
			$namespace = $this->core->info->title_namespace ( $title );
			if ( empty ( $namespace ) )
				$title = $this->core->info->namespace_name ( 'File' ) . ':' . $title;
		}

		if ( ! isset ( $this->properties ) )
			$this->properties = array();

		if ( ! isset ( $this->properties['imageinfo'] ) )
			$this->properties['imageinfo'] = array();

		if ( ! isset ( $this->properties['imageinfo']['prop'] ) )
			$this->properties['imageinfo']['prop'] =
				$this->core->info->param_querymodule_parameter_type ( 'prop', 'imageinfo' );

		return parent::fetch_and_propagate_batch();
	}


	# ----- Implemented ----- #


	protected function element_to_signal ( $element )
	{
		if ( isset ( $element['imageinfo'] ) && is_array ( $element['imageinfo'] ) )
		{
			$imageinfo = reset ( $element['imageinfo'] );
			if ( is_array ( $imageinfo ) )
				$element = array_merge  ( $element, $imageinfo );
			unset ( $element['imageinfo'] );
		}

		if ( $this->fetch_body )
		{
			if ( isset ( $this->body_file ) )
				if ( $this->body_file === "" )
					$this->core->browser->write_into_file = sha1 ( $element['title'] );
				else
					$this->core->browser->write_into_file = $this->body_file;

			if ( isset ( $element['url'] ) )
			{

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

			}
			else
			{
				$this->log ( "[[" . $element['title'] .
					"]] has no URL info - cannot fetch its body!", LL_WARNING );
			}

			unset ( $this->core->browser->write_into_file );

		}

		$file = new File ( $this->core, $element );

		return new LineSignal_Data ( $file, "object/file", NULL );
	}


}
