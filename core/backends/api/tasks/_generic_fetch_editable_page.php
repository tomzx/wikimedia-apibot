<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: generic Editable Page Fetch.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_fetch_page.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/query.php' );



abstract class API_Task_GenericFetchEditablePage extends API_Task_GenericFetchPage
{


	protected function fetch_editable_page ( $logbeg, $params )
	{
		$log_result =
			( isset ( $params['log_result'] ) ? $params['log_result'] : false );
		unset ( $params['log_result'] );

		if ( isset ( $params['properties'] ) )
			$properties = $params['properties'];
		else
			$properties = array();
		unset ( $params['properties'] );

		if ( ! isset ( $properties['revisions'] ) )
			$properties['revisions'] = array();

		if ( ! isset ( $properties['revisions']['prop'] ) )
			$properties['revisions']['prop'] = array (
				"content", "timestamp", "ids", "user", "comment", "size" );
		if ( ! isset ( $properties['revisions']['limit'] ) )
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

		if ( isset ( $params['revid'] ) )
		{
			$properties['revisions']['startid'] = $params['revid'];
			unset ( $params['revid'] );
		}
		if ( isset ( $params['section'] ) )
		{
			$properties['revisions']['section'] = $params['section'];
			unset ( $params['section'] );
		}

		$params['_prop'] = $properties;

		if ( $results = $this->act_and_log ( $logbeg, "fetched", $params ) )
		{
			list ( $pageid, $page ) = each ( $results['pages'] );

			if ( isset ( $page['revisions'] ) && is_array ( $page['revisions'] ) )
			{
				$revision = reset ( $page['revisions'] );

				if ( isset ( $revision['*'] ) )
					$page['text'     ] = $revision['*'];
				if ( isset ( $revision['timestamp'] ) )
					$page['timestamp'] = $revision['timestamp'];
				if ( isset ( $revision['revid'] ) )
					$page['revid'    ] = $revision['revid'];
				if ( isset ( $revision['parentid'] ) )
					$page['parentid' ] = $revision['parentid'];
				if ( isset ( $revision['size'] ) )
					$page['size'     ] = $revision['size'];
				if ( isset ( $revision['user'] ) )
					$page['user'     ] = $revision['user'];
				if ( isset ( $revision['comment'] ) )
					$page['comment'  ] = $revision['comment'];
			}

			if ( isset ( $properties['revisions']['section'] ) &&
				! is_null ( $properties['revisions']['section'] ) )

				$page['section'] = $properties['revisions']['section'];

			if ( isset ( $page['text'] ) )
				$page['md5'] = md5 ( $page['text'] );

			return $page;
		}
		else
		{
			return false;
		}

	}


}
