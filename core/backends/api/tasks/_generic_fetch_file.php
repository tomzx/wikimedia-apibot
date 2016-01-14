<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: Fetch file (ie. file page).
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_fetch_page.php' );



abstract class API_Task_GenericFetchFile extends API_Task_GenericFetchPage
{


	# ----- Fetching support ----- #

	protected function fetch_file ( $logbeg, $params )
	{
		$log_result =
			( isset ( $params['log_result'] ) ? $params['log_result'] : false );
		unset ( $params['log_result'] );

		if ( isset ( $params['properties'] ) )
			$properties = $params['properties'];
		else
			$properties = array();
		unset ( $params['properties'] );

		if ( ! isset ( $properties['imageinfo'] ) )
			$properties['imageinfo'] = array();

		if ( ! isset ( $properties['imageinfo']['prop'] ) )
			$properties['imageinfo']['prop'] = array (
				"timestamp", "user", "comment", "url", "size", "sha1" );
		if ( ! isset ( $properties['imageinfo']['limit'] ) )
			$properties['revisions']['limit'] = 1;

		if ( ! isset ( $properties['info'] ) )
			$properties['info'] = array ();
		if ( ! isset ( $properties['info']['prop'] ) )
			$properties['info']['prop'] = "protection";
		else
			if ( is_array ( $properties['info']['prop'] ) )
				if ( ! in_array ( "protection", $properties['info']['prop'] ) )
					$properties['info']['prop'][] = "protection";
			else
				if ( ! strpos ( $properties['info']['prop'], "protection" ) )
					$properties['info']['prop'] .= "|protection";

		$params['_prop'] = $properties;

		if ( $results = $this->act_and_log ( $logbeg, "fetched", $params ) )
		{
			list ( $pageid, $page ) = each ( $results['pages'] );

			if ( isset ( $page['imageinfo'] ) && is_array ( $page['imageinfo'] ) )
			{
				$imageinfo = reset ( $page['imageinfo'] );
				if ( is_array ( $imageinfo ) )
					$page = array_merge  ( $page, $imageinfo );
				unset ( $page['imageinfo'] );
			}

			return $page;
		}
		else
		{
			return false;
		}

	}


}
