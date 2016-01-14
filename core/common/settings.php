<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Mains: Settings.
#
#  Bot configuration settings storage and management.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #



class Bot_Settings
{

	protected $settings;


	# ----- Constructor ----- #

	function __construct ( $account, $settings, $task_settings = array() )
	{
		$this->settings = $settings;
		$this->set_account ( $account );
		$this->settings = $this->array_merge ( $this->settings, $task_settings );
	}


	# ----- Tools ----- #


	private function array_merge ( $array1, $array2 )  // the PHP array_merge() works otherwise!
	{
		if ( ! is_array ( $array2 ) )
			return $array2;

		foreach ( $array2 as $key => $value )
			if ( ! isset ( $array1[$key] ) )
				$array1[$key] = $value;
			else
				$array1[$key] = $this->array_merge ( $array1[$key], $array2[$key] );

		return $array1;
	}


	# ----- Settings access ----- #

	protected function get_member ( $trunk, $key1, $key2, $key3, $key4, $key5 )
	{
		$key1 = strtolower ( $key1 );

		if ( is_null ( $key1 ) )
			return NULL;
		elseif ( isset ( $trunk[$key1] ) )
			if ( is_null ( $key2 ) )
				return $trunk[$key1];
			else
				return $this->get_member ( $trunk[$key1],
					$key2, $key3, $key4, $key5, NULL );
		else
			return NULL;
	}


	protected function set_member ( &$trunk, $settings,
		$key1, $key2, $key3, $key4, $key5 )
	{
		if ( is_null ( $key1 ) )
		{
			$trunk = $settings;
			return true;
		}

		$key1 = strtolower ( $key1 );

		if ( ! isset ( $trunk[$key1] ) )
			$trunk[$key1] = array();

		if ( ! is_array ( $trunk[$key1] ) )
			return NULL;

		return $this->set_member ( $trunk, $settings,
			$key2, $key3, $key4, $key5, NULL );
	}


	public function get ( $key1, $key2 = NULL, $key3 = NULL, $key4 = NULL,
		$key5 = NULL )
	{
		return $this->get_member ( $this->settings,
			$key1, $key2, $key3, $key4, $key5 );
	}


	protected function merge_backend ( $backend_name, $settings )
	{
		$backend_name = strtolower ( $backend_name );

		if ( is_array ( $settings ) )
		{
			if ( isset ( $settings[$backend_name] ) )
			{
				$backend_array = $settings[$backend_name];
				$settings = array_merge ( $settings, $backend_array );
				unset ( $settings[$backend_name] );
			}

			foreach ( $settings as $key => $value )
				$settings[$key] = $this->merge_backend ( $backend_name, $value );
		}

		return $settings;
	}


	public function get_withbackend ( $backend_name,
		$key1, $key2 = NULL, $key3 = NULL, $key4 = NULL, $key5 = NULL )
	{
		return $this->merge_backend ( $backend_name,
			$this->get (  $key1, $key2, $key3, $key4, $key5 ) );
	}


	public function set ( $settings,
		$key1 = NULL, $key2 = NULL, $key3 = NULL, $key4 = NULL, $key5 = NULL )
	{
		return $this->set_member ( $this->settings, $settings,
			$key1, $key2, $key3, $key4, $key5 );
	}


	# Requires a settings block - does not touch $this->settings!
	public function merge ( $settings,
		$key1 = NULL, $key2 = NULL, $key3 = NULL, $key4 = NULL, $key5 = NULL )
	{
		if ( ! is_null ( $key1 ) && isset ( $settings[$key1] ) )
			return $this->merge ( $settings[$key1], $key2, $key3, $key4, $key5, NULL );

		$key1 = strtolower ( $key1 );

		if ( isset ( $settings[$key1] ) && is_array ( $settings[$key1] ) )
			foreach ( $settings[$key1] as $key => $value )
				$settings[$key] = $value;

		return $settings;
	}



	public function set_account ( $account )
	{
		$wiki = ( isset ( $account['wiki'] ) ? $account['wiki'] : array() );
		unset ( $account['wiki'] );

		# Migrating old URLs syntax to the current:
		if ( ! isset ( $wiki['urls'] ) )
			$wiki['urls'] = array();
		if ( isset ( $wiki['api_url'] ) && ! isset ( $wiki['urls']['api'] ) )
		{
			$wiki['urls']['api'] = $wiki['api_url'];
			unset ( $wiki['api_url'] );
		}
		if ( isset ( $wiki['web_url'] ) && ! isset ( $wiki['urls']['web'] ) )
		{
			$wiki['urls']['web'] = $wiki['web_url'];
			unset ( $wiki['web_url'] );
		}

		# Setting Web url from API, if not specified:
		if ( ! isset ( $wiki['urls']['web'] ) && isset ( $wiki['urls']['api'] ) )
			$wiki['urls']['web'] =
				str_replace ( 'api.php', 'index.php', $wiki['urls']['api'] );

		# Preparing wiki data for merging with settings
		if ( ! isset ( $wiki['wiki'] ) )
			$wiki['wiki'] = array();
		if ( ! isset ( $wiki['wiki']['name'] ) && isset ( $wiki['name'] ) )
			$wiki['wiki']['name'] = $wiki['name'];
		unset ( $wiki['name'] );
		if ( ! isset ( $wiki['wiki']['urls'] ) && isset ( $wiki['urls'] ) )
			$wiki['wiki']['urls'] = $wiki['urls'];
		unset ( $wiki['urls'] );

		# Setting wiki software default:
		if ( ! isset ( $wiki['wiki']['software'] ) )
			$wiki['wiki']['software'] = "MediaWiki";

		$this->settings = $this->array_merge ( $this->settings, $wiki );

		# Preparing account data for merging with settings
		if ( ! isset ( $account['account'] ) )
			$account['account'] = array();
		if ( ! isset ( $account['account']['user'] ) && isset ( $account['user'] ) )
			$account['account']['user'] = $account['user'];
		unset ( $account['user'] );
		if ( ! isset ( $account['account']['password'] ) && isset ( $account['password'] ) )
			$account['account']['password'] = $account['password'];
		unset ( $account['password'] );

		$this->settings = $this->array_merge ( $this->settings, $account );
	}


}
