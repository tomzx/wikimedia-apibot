<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Task: Generic fetch page.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #

require_once ( dirname ( __FILE__ ) . '/_generic_fetch.php' );
require_once ( dirname ( __FILE__ ) . '/../data/page.php' );



abstract class Task_Fetch_Page extends Task_Fetch
{

	# ----- Tools ----- #

	protected function antibot_template_protected_text ( $text, $username )
	{
		return (bool) preg_match (
			'/\{\{(' .
				'nobots|' .
				'bots\|allow=none|' .
				'bots\|deny=all|' .
				'bots\|optout=all|' .
				'bots\|deny[^\|\}]*[\=\,]\s*' .
					preg_quote ( $username, '/' ) . '\s*[\,\|\}]' .
			')\}\}/iS',
		$text );
	}


	# ----- Overriding ----- #

	protected function postprocess_result ( $page )
	{
		if ( ( $page === false ) || ( $page === NULL ) )
			return $page;

		$page = parent::postprocess_result ( $page );

		if ( isset ( $page['text'] ) )
		{
			$page['nobottemplate'] =
				$this->antibot_template_protected_text ( $page['text'],
					$this->core->info->user_name() );
			$page['md5'] = md5 ( $page['text'] );
		}

		if ( $this->settings['fetch_objects'] )
			return new Page ( $this->core, $page );
		else
			return $page;
	}


}
