<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Backend: Identity.
#
#  Provides login, logout and other identity management.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../../_generic/identity.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/login.php' );
require_once ( dirname ( __FILE__ ) . '/../modules/logout.php' );



class Identity_API extends Identity
{


	# ----- Implemented ----- #

	protected function set_cookies_names ( $cookieprefix )
	{
		$this->cookies_names = array (
			$cookieprefix . "UserID",
			$cookieprefix . "UserName",
			$cookieprefix . "Token",  // important for some wikis
			'centralauth_User',
			'centralauth_Token',
//			'centralauth_Session',  // todo! check its effect on bot work, eg. editing pages!
		);
	}


	protected function full_login ( $account, $wiki )
	{
		if ( empty ( $account['user'] ) || empty ( $account['password'] ) )
		{
			$this->log ( "Not logged in - account or password not specified", LL_INFO );
			return true;  // not logged in because no account data specified - all is OK
		}

		# mw version may be still unknown
		$API_Login = new API_Module_Login ( $this->exchanger, NULL, $this->hooks, NULL );

		$params = array (
			'name'     => $account['user'],
			'password' => $account['password']
		);
		if ( isset ( $account['domain'] ) )
			$params['domain'] = $account['domain'];

		$API_Login->set_params ( $params );

		while ( $result = $API_Login->xfer() )
		{
			switch ( $API_Login->data['login']['result'] )
			{
				case "Success" :
					$this->set_cookies_names ( $API_Login->data['login']['cookieprefix'] );
					break 2;

				case "NeedToken" :
					$API_Login->set_params ( $params );
					$API_Login->set_param ( 'token', $API_Login->data['login']['token'] );
					if ( isset ( $API_Login->data['login']['wait'] ) )
						sleep ( $API_Login->data['login']['wait'] );
					break;

				case "Throttled" :  // might not be needed - handled by the exchanger, too
					$API_Login->set_params ( $params );
					$throttled_wait =
						( $API_Login->data['login']['wait'] / 10 ) + 10;  // to be on the safe side
					$this->log ( "Throttled - waiting for " . $throttled_wait .
						" secs...", LL_INFO );
					sleep ( $throttled_wait );
					break;

				case "NotExists" :
					$this->log ( "The account specified (" . $account['user'] . " at " .
						$wiki['name'] . ") does not exist!", LL_ERROR );
					if ( $account['user'] !== "ANONYMOUS" )
						die();
					return false;

				default :
					throw new ApibotException_LoginError (
						$API_Login->data['login']['result'] .
						( isset ( $API_Login->data['login']['details'] )
						  ? ": " . $API_Login->data['login']['details']
						  : "" ) );
			}
		}

		if ( $result )
			$this->log ( "Logged in " . $wiki['name'] . " as " .
				$account['user'], LL_INFO );
		else
			$this->log ( "Could not log in " . $wiki['name'] . " as " .
				$account['user'] . "!", LL_ERROR );

		return $result;
	}


	protected function full_logout ()
	{
		$API_Logout = new API_Module_Logout ( $this->exchanger, NULL, $this->hooks, NULL );
		return $API_Logout->xfer();
	}


}
