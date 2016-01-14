<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Task: Generic fetch.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #

require_once ( dirname ( __FILE__ ) . '/_generic.php' );



abstract class Task_Fetch extends Task
{

	# ----- Overriding ----- #

	protected function default_settings ()
	{
		$settings = parent::default_settings();

		if ( ! isset ( $settings['fetch_objects'] ) )
			$settings['fetch_objects'] = true;

		if ( ! isset ( $settings['log_fetch'] ) )
			$settings['log_fetch'] = true;

		return $settings;
	}


	protected function postprocess_result ( $data )
	{
		$remote_time = $this->core->info->remote_time();
		if ( ! is_null ( $remote_time ) )
			$data['fetchtimestamp'] = date ( 'Y-m-d\TH:i:s\Z', $remote_time );

		return $data;
	}


}
