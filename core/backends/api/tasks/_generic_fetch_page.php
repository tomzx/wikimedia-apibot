<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Task: generic Page Fetch.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_fetch.php' );
require_once ( dirname ( __FILE__ ) . '/../actions/query.php' );



abstract class API_Task_GenericFetchPage extends API_Task_GenericFetch
{


	# ----- Overriding ----- #


	protected function check_result ( $query, $logbeg, $actdesc )
	{
		if ( isset ( $query->data['query']['pages'] ) )
		{
			$pageid = key ( $query->data['query']['pages'] );
			$page = $query->data['query']['pages'][$pageid];

			if ( isset ( $page['missing'] ) )
			{
				if ( isset ( $page['imagerepository'] ) &&
					( $page['imagerepository'] == "shared" ) )
				{
					$this->log ( $logbeg . " is in a shared repository",
						LL_WARNING, $this->logpreface );
					return true;
				}
				else
				{
					$this->log ( $logbeg . " is missing",
						LL_WARNING, $this->logpreface );
				}
				return false;

			}
			elseif ( isset ( $page['invalid'] ) )
			{
				$this->log ( $logbeg . " is invalid",
					LL_WARNING, $this->logpreface );
				return false;

			}
			elseif ( ( $pageid < 0 ) )
			{
				$this->log ( $logbeg . " is unavailable",
					LL_WARNING, $this->logpreface );
				return false;
			}

			return true;
		}
		else
		{
			return false;
		}
	}


	protected function resolve_element ( $struct, $element_name )
	{
		if ( is_object ( $struct ) )
			if ( isset ( $struct->$element_name ) )
				return $struct->element_name;
			else
				return NULL;

		elseif ( is_array ( $struct ) )
			if ( isset ( $struct[$element_name] ) )
				return $struct[$element_name];
			else
			{
				$result = array();

				foreach ( $struct as $key => $value )
					$result[$key] = parent::resolve_element ( $value, $element_name );

				return $result;
			}

		return $struct;
	}


	# ----- Implemented ----- #


	protected function api_action ()
	{
		return new API_Action_Query ( $this->backend );
	}


	protected function action_rights ()
	{
		return array ( "read" );
	}


}
