<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Line signals classes.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #



abstract class LineSignal_Generic
{

	public $id;

	public $log = array();
	public $params = array();


	# ----- Constructor ----- #

	function __construct ( $params = NULL )
	{
		$this->id = rand ( 0, PHP_INT_MAX );
		$this->params = $params;
	}


	# ----- Signal slots logging ----- #

	public function log_slot ( $slot_type, $slot_name, $slot_job )
	{
		$this->log[] = array (
			'type' => $slot_type,
			'name' => $slot_name,
			'job'  => $slot_job,
			'time' => date ( 'Y-m-d H:i:s', time() ),
		);
	}


	# ----- Signal slot params support ----- #

	public function exists_paramgroup ( $group_name )
	{
		return isset ( $this->params[$group_name] );
	}

	public function unset_paramgroup ( $group_name )
	{
		if ( isset ( $this->params[$group_name] ) )
			unset ( $this->params[$group_name] );
		return true;
	}

	public function copy_paramgroup ( $group_name, $to_name )
	{
		if ( isset ( $this->params[$group_name] ) )
		{
			$this->params[$to_name] = $this->params[$group_name];
			return true;
		}
		else
		{
			return false;
		}
	}

	public function rename_paramgroup ( $group_name, $to_name )
	{
		if ( $this->copy_paramgroup ( $group_name, $to_name ) )
		{
			$this->unset_paramgroup ( $group_name );
			return true;
		}
		else
		{
			return false;
		}
	}

	public function swap_paramgroups ( $group_name1, $group_name2 )
	{
		if ( isset ( $this->params[$group_name1] ) &&
			isset ( $this->params[$group_name2] ) )
		{
			$temp = $this->params[$group_name1];
			$this->params[$group_name1] = $this->params[$group_name2];
			$this->params[$group_name2] = $temp;
		}
		elseif ( isset ( $this->params[$group_name1] ) )
		{
			$this->rename_paramgroup ( $group_name1, $group_name2 );
		}
		elseif ( isset ( $this->params[$group_name2] ) )
		{
			$this->rename_paramgroup ( $group_name2, $group_name1 );
		}
		else
		{
			return false;
		}
		return true;
	}


	public function exists_param ( $group_name, $param_name )
	{
		return isset ( $this->params[$group_name][$param_name] );
	}

	public function unset_param ( $group_name, $param_name )
	{
		if ( $this->exists_param ( $group_name, $param_name ) )
			unset ( $this->params[$group_name][$param_name] );
		return true;
	}

	public function copy_param ( $group_name, $param_name, $to_param_name,
		$to_group_name = NULL )
	{
		if ( $this->exists_param ( $group_name, $param_name ) )
		{
			if ( is_null ( $to_group_name ) )
				$to_group_name = $group_name;
			if ( ! $this->exists_paramgroup ( $to_group_name ) )
				$this->params[$to_group_name] = array();
			$this->params[$to_group_name][$to_param_name] =
				$this->params[$group_name][$param_name];
			return true;
		}
		else
		{
			return false;
		}
	}

	public function rename_param ( $group_name, $param_name, $to_param_name,
		$to_group_name = NULL )
	{
		if ( $this->copy_param ( $group_name, $param_name, $to_param_name,
			$to_group_name ) )
		{
			$this->unset_param ( $group_name, $param_name );
			return true;
		}
		else
		{
			return false;
		}
	}

	public function swap_params ( $group_name1, $param_name1, $group_name2,
		$param_name2 )
	{
		if ( isset ( $this->params[$group_name1][$param_name1] ) &&
			isset ( $this->params[$group_name2][$param_name2] ) )
		{
			$temp = $this->params[$group_name1][$param_name1];
			$this->params[$group_name1][$param_name1] =
				$this->params[$group_name2][$param_name2];
			$this->params[$group_name2][$param_name2] = $temp;
		}
		elseif ( isset ( $this->params[$group_name1][$param_name1] ) )
		{
			$this->rename_param ( $group_name1, $param_name1, $param_name2,
				$group_name2 );
		}
		elseif ( isset ( $this->params[$group_name2][$param_name2] ) )
		{
			$this->rename_param ( $group_name2, $param_name2, $param_name1,
				$group_name1 );
		}
		else
		{
			return false;
		}
		return true;
	}



	public function paramgroup ( $group_name )
	{
		return ( isset ( $this->params[$group_name] )
			? $this->params[$group_name]
			: NULL );
	}

	public function set_paramgroup ( $group_name, $group = array() )
	{
		$this->params[$group_name] = $group;
		return true;
	}


	public function param ( $group_name, $param_name )
	{
		$group = $this->paramgroup ( $group_name );
		if ( is_array ( $group ) )
			return ( isset ( $group[$param_name] )
				? $group[$param_name]
				: NULL );
	}

	public function set_param ( $group_name, $param_name, $param )
	{
		if ( ! $this->exists_paramgroup ( $group_name ) )
			$this->set_paramgroup ( $group_name );
		$this->params[$group_name][$param_name] = $param;
		return true;
	}


}



class LineSignal_Start extends LineSignal_Generic
{

	# ----- Constructor ----- #

	function __construct ( $params = NULL )
	{
		parent::__construct ( $params );
	}


}



class LineSignal_End extends LineSignal_Generic
{

	# ----- Constructor ----- #

	function __construct ( $params = NULL )
	{
		parent::__construct ( $params );
	}


}



class LineSignal_Data extends LineSignal_Generic
{

	protected $data = array();

	# ----- Constructor ----- #

	function __construct ( $element, $element_type, $element_key = NULL,
		$params = NULL )
	{
		parent::__construct ( $params );

		$block = $this->make_data_block ( $element, $element_type, $element_key );
		$this->set_data_block ( '*', $block );
	}


	# ----- Data handling ----- #

	private function make_data_block ( $element, $element_type, $element_key )
	{
		return array (
			'*' => $element,
			'type' => $element_type,
			'key' => $element_key,
		);
	}


	public function all_data ()
	{  // debug-only
		return $this->data;
	}

	public function all_data_keys ()
	{  // debug-only
		return array_keys ( $this->data );
	}


	public function exists_data_block ( $data_key )
	{
		return isset ( $this->data[$data_key] );
	}

	public function create_data_block ( $data_key,
		$element, $element_type, $element_key = NULL )
	{
		$this->data[$data_key] =
			$this->make_data_block ( $element, $element_type, $element_key );
	}

	public function unset_data_block ( $data_key )
	{
		unset ( $this->data[$data_key] );
		return true;
	}

	public function copy_data_block ( $from_key, $to_key )
	{
		if ( isset ( $this->data[$from_key] ) )
		{
			$this->data[$to_key] = $this->data[$from_key];
			return true;
		}
		else
		{
			return false;
		}
	}

	public function rename_data_block ( $old_key, $new_key )
	{
		if ( $this->copy_data_block ( $old_key, $new_key ) )
		{
			$this->unset_data_block ( $old_key );
			return true;
		}
		else
		{
			return false;
		}
	}

	public function swap_data_blocks ( $key1, $key2 )
	{
		if ( isset ( $this->data[$key1] ) && isset ( $this->data[$key2] ) )
		{
			$temp = $this->data[$key1];
			$this->data[$key1] = $this->data[$key2];
			$this->data[$key2] = $temp;
		}
		elseif ( isset ( $this->data[$key1] ) )
		{
			$this->data[$key2] = $this->data[$key1];
			unset ( $this->data[$key1] );
		}
		elseif ( isset ( $this->data[$key2] ) )
		{
			$this->data[$key1] = $this->data[$key2];
			unset ( $this->data[$key2] );
		}
		else
		{
			return false;
		}
		return true;
	}


	public function data_block ( $data_key )
	{
		return ( isset ( $this->data[$data_key] )
			? $this->data[$data_key]
			: NULL );
	}

	public function data_element ( $data_key )
	{
		$block = $this->data_block ( $data_key );
		return ( is_array ( $block )
			? $block['*']
			: NULL );
	}

	public function data_type ( $data_key )
	{
		$block = $this->data_block ( $data_key );
		return ( is_array ( $block )
			? $block['type']
			: NULL );
	}

	public function data_element_key ( $data_key )
	{
		$block = $this->data_block ( $data_key );
		return ( is_array ( $block )
			? $block['key']
			: NULL );
	}


	public function set_data_block ( $data_key, $block )
	{
		$this->data[$data_key] = $block;
	}

	public function set_data_element ( $data_key, $element )
	{
		if ( ! isset ( $this->data[$data_key] ) )
			$this->data[$data_key] = array();
		$this->data[$data_key]['*'] = $element;
	}

	public function set_data_type ( $data_key, $type )
	{
		if ( ! isset ( $this->data[$data_key] ) )
			$this->data[$data_key] = array();
		$this->data[$data_key]['type'] = $type;
	}

	public function set_data_element_key ( $data_key, $element_key )
	{
		if ( ! isset ( $this->data[$data_key] ) )
			$this->data[$data_key] = array();
		$this->data[$data_key]['key'] = $element_key;
	}

	public function data_element_property_exists ( $data_key, $property_key )
	{
		if ( is_object ( $this->data[$data_key] ) )
			return isset ( $this->data[$data_key]->$property_key );
		elseif ( is_array ( $this->data[$data_key] ) )
			return isset ( $this->data[$data_key][$property_key] );
		 else
			return NULL;
	}

	public function data_element_property ( $data_key, $property_key )
	{
		if ( is_object ( $this->data[$data_key] ) &&
			isset ( $this->data[$data_key]->$property_key ) )
		{
			return $this->data[$data_key]->$property_key;
		}
		elseif ( is_array ( $this->data[$data_key] ) &&
			isset ( $this->data[$data_key][$property_key] ) )
		{
			return $this->data[$data_key][$property_key];
		}
		 else
		{
			return NULL;
		}
	}

	public function set_data_element_property ( $data_key, $property_key, $value )
	{
		if ( is_object ( $this->data[$data_key] ) )
		{
			$this->data[$data_key]->$property_key = $value;
			return true;
		}
		elseif ( is_array ( $this->data[$data_key] ) )
		{
			$this->data[$data_key][$property_key] = $value;
			return true;
		}
		else
		{
			return NULL;
		}
	}


	# ----- Extracting specific data elements ----- #

	# --- Tools --- #

	private function prepared_data_element ( $data_key )
	{
		$data = $this->data_block ( $data_key );
		if ( ( strpos ( $data['type'], 'object/' ) !== false ) &&
			( $data['*'] instanceof Dataobject ) )
		{
			$data['*'] = $data['*']->data();
			$data['type'] = str_replace ( 'object/', 'array/', $data['type'] );
		}
		return $data;
	}


	private function data_element_numeric ( $data, $key )
	{
		if ( strpos ( $data['type'], '*/' ) !== false )
			if ( is_numeric ( $data['*'] ) )
				return $data['*'];
			elseif ( is_array ( $data['*'] ) && isset ( $data['*'][$key] ) )
				return $data['*'][$key];
			else
				return NULL;

		if ( strpos ( $data['type'], 'string/' ) !== false )
			if ( is_numeric ( $data['*'] ) )
				return $data['*'];
			else
				return NULL;

		if ( strpos ( $data['type'], 'numeric/' ) !== false )
			return $data['*'];

		return false;
	}

	private function data_element_string ( $data, $key )
	{
		if ( strpos ( $data['type'], '*/' ) !== false )
			if ( is_string ( $data['*'] ) || is_numeric ( $data['*'] ) )
				return $data['*'];
			elseif ( is_array ( $data['*'] ) && isset ( $data['*'][$key] ) )
				return $data['*'][$key];
			else
				return NULL;

		if ( strpos ( $data['type'], 'string/' ) !== false )
			return $data['*'];

		if ( strpos ( $data['type'], 'numeric/' ) !== false )
			return $data['*'];

		return false;
	}

	private function data_element_timestamp ( $data, $key )
	{
		if ( strpos ( $data['type'], '*/' ) !== false )
			if ( is_string ( $data['*'] ) && ( strtotime ( $data['*'] ) !== false ) )
				return $data['*'];
			elseif ( is_array ( $data['*'] ) && isset ( $data['*'][$key] ) )
				return $data['*'][$key];
			else
				return NULL;

		if ( strpos ( $data['type'], 'string/' ) !== false )
			if ( strtotime ( $data['*'] ) !== false )
				return $data['*'];
			else
				return NULL;

		if ( strpos ( $data['type'], 'numeric/' ) !== false )
			return date ( 'Y-m-d H:i:s', $data['*'] );

		return false;
	}


	# --- External access --- #

	public function data_title ( $data_key )
	{
		$data = $this->prepared_data_element ( $data_key );
		if ( is_null ( $data ) )
			return NULL;

		$result = $this->data_element_string ( $data, 'title' );
		if ( $result !== false )
			return $result;

		if ( is_object ( $data['*'] ) && ( $data['*'] instanceof Dataobject ) )
			$data['*'] = $data['*']->data();

		switch ( $data['type'] )
		{
			case 'array/category' :
			case 'array/langlink' :
				if ( isset ( $data['*']['*'] ) )
					return $data['*']['*'];

			case 'array/link' :
			case 'array/page' :
			case 'array/file' :
			case 'array/logevent' :
			case 'array/protectedtitle' :
			case 'array/querypage' :
			case 'array/randompage' :
			case 'array/recentchange' :
			case 'array/usercontrib' :
			case 'array/category' :
			case 'array/link' :
			case 'array/template' :
			case 'array/revision' :
				if ( isset ( $data['*']['title'] ) )
					return $data['*']['title'];

			case 'array/info' :
				if ( $data['key'] === "title" )
					return $data['*'];

			case 'array/user' :
				if ( isset ( $data['*']['name'] ) )
					return 'User:' . $data['*']['name']; // dirty hack - will require Info object access for a nice result

			case 'array/image' :
			case 'array/imageinfo' :
				if ( isset ( $data['*']['title'] ) )
					return $data['*']['title'];
				elseif ( isset ( $data['*']['name'] ) )
					return 'File:' . $data['*']['name'];  // dirty hack - will require Info object access for a nice result

			case 'array/duplicatefile' :
				if ( isset ( $data['*']['name'] ) && isset ( $data['*']['ns'] ) )
					return 'File:' . $data['*']['name']; // dirty hack - will require Info object access for a nice result
		}

		return NULL;
	}


	public function data_pageid ( $data_key )
	{
		$data = $this->prepared_data_element ( $data_key );
		if ( is_null ( $data ) )
			return NULL;

		$result = $this->data_element_numeric ( $data, 'pageid' );
		if ( $result !== false )
			return $result;

		if ( is_object ( $data['*'] ) && ( $data['*'] instanceof Dataobject ) )
			$data['*'] = $data['*']->data();

		switch ( $data['type'] )
		{
			case 'array/page' :
			case 'array/file' :
			case 'array/logevent' :
			case 'array/recentchange' :
			case 'array/usercontrib' :
			case 'array/revision' :
				if ( isset ( $data['*']['pageid'] ) )
					return $data['*']['pageid'];

			case 'array/randompage' :
				if ( isset ( $data['*']['id'] ) )
					return $data['*']['id'];

			case 'array/info' :
				if ( $data['key'] === "pageid" )
					return $data['*'];
		}

		return NULL;
	}


	public function data_revid ( $data_key )
	{
		$data = $this->prepared_data_element ( $data_key );
		if ( is_null ( $data ) )
			return NULL;

		$result = $this->data_element_numeric ( $data, 'revid' );
		if ( $result !== false )
			return $result;

		if ( is_object ( $data['*'] ) && ( $data['*'] instanceof Dataobject ) )
			$data['*'] = $data['*']->data();

		switch ( $data['type'] )
		{
			case 'array/recentchange' :
			case 'array/usercontrib' :
			case 'array/revision' :
				if ( isset ( $data['*']['revid'] ) )
					return $data['*']['revid'];

			case 'array/page' :
				if ( isset ( $data['*']['revid'] ) )
					return $data['*']['revid'];
				elseif ( isset ( $data['*']['lastrevid'] ) )
					return $data['*']['lastrevid'];

			case 'array/info' :
				if ( $data['key'] === "lastrevid" )
					return $data['*'];
		}

		return NULL;
	}


	public function data_user ( $data_key )
	{
		$data = $this->prepared_data_element ( $data_key );
		if ( is_null ( $data ) )
			return NULL;

		$result = $this->data_element_string ( $data, 'user' );
		if ( $result !== false )
			return $result;

		if ( is_object ( $data['*'] ) && ( $data['*'] instanceof Dataobject ) )
			$data['*'] = $data['*']->data();

		switch ( $data['type'] )
		{
			case 'array/page' :
			case 'array/file' :
			case 'array/block' :
			case 'array/logevent' :
			case 'array/protectedtitle' :
			case 'array/recentchange' :
			case 'array/usercontrib' :
			case 'array/revision' :
			case 'array/duplicatefile' :
				if ( isset ( $data['*']['user'] ) )
					return $data['*']['user'];

			case 'array/user' :
				if ( isset ( $data['*']['name'] ) )
					return $data['*']['name'];

			case 'array/imageinfo' :
				if ( $data['key'] === "user" )
					return $data['*'];
		}

		return NULL;
	}


	public function data_by_user ( $data_key )
	{
		$data = $this->prepared_data_element ( $data_key );
		if ( is_null ( $data ) )
			return NULL;

		$result = $this->data_element_string ( $data, 'by' );
		if ( $result !== false )
			return $result;

		if ( is_object ( $data['*'] ) && ( $data['*'] instanceof Dataobject ) )
			$data['*'] = $data['*']->data();

		switch ( $data['type'] )
		{
			case 'array/block' :
				if ( isset ( $data['*']['by'] ) )
					return $data['*']['by'];
		}

		return NULL;
	}


	public function data_userid ( $data_key )
	{
		$data = $this->prepared_data_element ( $data_key );
		if ( is_null ( $data ) )
			return NULL;

		$result = $this->data_element_numeric ( $data, 'userid' );
		if ( $result !== false )
			return $result;

		if ( is_object ( $data['*'] ) && ( $data['*'] instanceof Dataobject ) )
			$data['*'] = $data['*']->data();

		switch ( $data['type'] )
		{
			case 'array/user' :
			case 'array/block' :
			case 'array/protectedtitle' :
			case 'array/recentchange' :
			case 'array/usercontrib' :
				if ( isset ( $data['*']['userid'] ) )
					return $data['*']['userid'];
		}

		return NULL;
	}


	public function data_by_userid ( $data_key )
	{
		$data = $this->prepared_data_element ( $data_key );
		if ( is_null ( $data ) )
			return NULL;

		$result = $this->data_element_numeric ( $data, 'byid' );
		if ( $result !== false )
			return $result;

		if ( is_object ( $data['*'] ) && ( $data['*'] instanceof Dataobject ) )
			$data['*'] = $data['*']->data();

		switch ( $data['type'] )
		{
			case 'array/block' :
				if ( isset ( $data['*']['byid'] ) )
					return $data['*']['byid'];
		}

		return NULL;
	}


	public function data_timestamp ( $data_key )
	{
		$data = $this->prepared_data_element ( $data_key );
		if ( is_null ( $data ) )
			return NULL;

		$result = $this->data_element_timestamp ( $data, 'timestamp' );
		if ( $result !== false )
			return $result;

		if ( is_object ( $data['*'] ) && ( $data['*'] instanceof Dataobject ) )
			$data['*'] = $data['*']->data();

		switch ( $data['type'] )
		{
			case 'array/image' :
			case 'array/page' :
			case 'array/file' :
			case 'array/logevent' :
			case 'array/block' :
			case 'array/protectedtitle' :
			case 'array/recentchange' :
			case 'array/usercontrib' :
			case 'array/revision' :
			case 'array/category' :
			case 'array/duplicatefile' :
				if ( isset ( $data['*']['timestamp'] ) )
					return $data['*']['timestamp'];

			case 'array/querypage' :
				if ( isset ( $data['*']['cachedtimestamp'] ) )
					return $data['*']['cachedtimestamp'];

			case 'array/info' :
				if ( $data['key'] === "touched" )
					return $data['*'];

			case 'array/imageinfo' :
				if ( $data['key'] === "timestamp" )
					return $data['*'];
		}

		return NULL;
	}


	public function data_expiry ( $data_key )
	{
		$data = $this->prepared_data_element ( $data_key );
		if ( is_null ( $data ) )
			return NULL;

		$result = $this->data_element_timestamp ( $data, 'expiry' );
		if ( $result !== false )
			return $result;

		if ( is_object ( $data['*'] ) && ( $data['*'] instanceof Dataobject ) )
			$data['*'] = $data['*']->data();

		switch ( $data['type'] )
		{
			case 'array/block' :
			case 'array/protectedtitle' :
				if ( isset ( $data['*']['expiry'] ) )
					return $data['*']['expiry'];
		}

		return NULL;
	}


	public function data_nsid ( $data_key )
	{
		$data = $this->prepared_data_element ( $data_key );
		if ( is_null ( $data ) )
			return NULL;

		$result = $this->data_element_numeric ( $data, 'ns' );
		if ( $result !== false )
			return $result;

		if ( is_object ( $data['*'] ) && ( $data['*'] instanceof Dataobject ) )
			$data['*'] = $data['*']->data();

		switch ( $data['type'] )
		{
			case 'array/image' :
			case 'array/link' :
			case 'array/page' :
			case 'array/file' :
			case 'array/logevent' :
			case 'array/querypage' :
			case 'array/randompage' :
			case 'array/recentchange' :
			case 'array/usercontrib' :
			case 'array/link' :
			case 'array/template' :
			case 'array/image' :
				if ( isset ( $data['*']['ns'] ) )
					return $data['*']['ns'];

			case 'array/category' :
				if ( isset ( $data['*']['ns'] ) )
					return $data['*']['ns'];
				else
					return 14;

			case 'array/info' :
			case 'array/imageinfo' :
				if ( $data['key'] === "ns" )
					return $data['*'];
		}

		return NULL;
	}


	public function data_rcid ( $data_key )
	{
		$data = $this->prepared_data_element ( $data_key );
		if ( is_null ( $data ) )
			return NULL;

		$result = $this->data_element_numeric ( $data, 'rcid' );
		if ( $result !== false )
			return $result;

		if ( is_object ( $data['*'] ) && ( $data['*'] instanceof Dataobject ) )
			$data['*'] = $data['*']->data();

		switch ( $data['type'] )
		{
			case 'array/recentchange' :
				if ( isset ( $data['*']['rcid'] ) )
					return $data['*']['rcid'];
		}

		return NULL;
	}


	public function data_logid ( $data_key )
	{
		$data = $this->prepared_data_element ( $data_key );
		if ( is_null ( $data ) )
			return NULL;

		$result = $this->data_element_numeric ( $data, 'logid' );
		if ( $result !== false )
			return $result;

		if ( is_object ( $data['*'] ) && ( $data['*'] instanceof Dataobject ) )
			$data['*'] = $data['*']->data();

		switch ( $data['type'] )
		{
			case 'array/logevent' :
			case 'array/recentchange' :
				if ( isset ( $data['*']['logid'] ) )
					return $data['*']['logid'];
		}

		return NULL;
	}


	public function data_blockid ( $data_key )
	{
		$data = $this->prepared_data_element ( $data_key );
		if ( is_null ( $data ) )
			return NULL;

		$result = $this->data_element_numeric ( $data, 'blockid' );
		if ( $result !== false )
			return $result;

		if ( is_object ( $data['*'] ) && ( $data['*'] instanceof Dataobject ) )
			$data['*'] = $data['*']->data();

		switch ( $data['type'] )
		{
			case 'array/block' :
				if ( isset ( $data['*']['id'] ) )
					return $data['*']['id'];
		}

		return NULL;
	}


	public function data_size ( $data_key )
	{
		$data = $this->prepared_data_element ( $data_key );
		if ( is_null ( $data ) )
			return NULL;

		$result = $this->data_element_numeric ( $data, 'size' );
		if ( $result !== false )
			return $result;

		if ( is_object ( $data['*'] ) && ( $data['*'] instanceof Dataobject ) )
			$data['*'] = $data['*']->data();

		switch ( $data['type'] )
		{
			case 'array/page' :
			case 'array/file' :
			case 'array/revision' :
				if ( isset ( $data['*']['size'] ) )
					return $data['*']['size'];

			case 'array/imageinfo' :
				if ( $data['key'] === "size" )
					return $data['*'];
		}

		return NULL;
	}


	public function data_extlink ( $data_key )
	{
		$data = $this->prepared_data_element ( $data_key );
		if ( is_null ( $data ) )
			return NULL;

		$result = $this->data_element_string ( $data, 'extlink' );
		if ( $result !== false )
			return $result;

		if ( is_object ( $data['*'] ) && ( $data['*'] instanceof Dataobject ) )
			$data['*'] = $data['*']->data();

		switch ( $data['type'] )
		{
			case 'array/extlink' :
				if ( isset ( $data['*']['*'] ) )
					return $data['*']['*'];
		}

		return NULL;
	}


	public function data_to_title ( $data_key )
	{
		$data = $this->prepared_data_element ( $data_key );
		if ( is_null ( $data ) )
			return NULL;

		$result = $this->data_element_string ( $data, 'to' );
		if ( $result !== false )
			return $result;
		$result = $this->data_element_string ( $data, 'to_title' );
		if ( $result !== false )
			return $result;

		if ( is_object ( $data['*'] ) && ( $data['*'] instanceof Dataobject ) )
			$data['*'] = $data['*']->data();

		switch ( $data['type'] )
		{
			case 'array/page' :
				if ( isset ( $data['*']['to'] ) )
					return $data['*']['to'];
				elseif ( isset ( $data['*']['to_title'] ) )
					return $data['*']['to_title'];
		}

		return NULL;
	}


	public function data_unique_id ( $data_key )
	{
		$id = NULL;

		if ( is_null ( $id ) )
			$id = $this->data_rcid ( $data_key );

		if ( is_null ( $id ) )
			$id = $this->data_logid ( $data_key );

		if ( is_null ( $id ) )
			$id = $this->data_revid ( $data_key );

		if ( is_null ( $id ) )
			$id = $this->data_pageid ( $data_key );

		if ( is_null ( $id ) )
			$id = $this->data_title ( $data_key );

		if ( is_null ( $id ) )
			$id = $this->data_blockid ( $data_key );

		if ( is_null ( $id ) )
			$id = $this->data_userid ( $data_key );

		if ( is_null ( $id ) )
			$id = $this->data_user ( $data_key );

		if ( is_null ( $id ) )
			$id = $this->data_nsid ( $data_key );

		if ( is_null ( $id ) )
			$id = $this->data_timestamp ( $data_key );  // not the best id :-(

		if ( is_null ( $id ) )
		{
			$data = $this->data_block ( $data_key );
			if ( strpos ( $data['type'], 'string/' ) !== false )
				$id = $data['*'];
			elseif ( strpos ( $data['type'], 'numeric/' ) !== false )
				$id = $data['*'];
			elseif ( strpos ( $data['type'], '*/' ) !== false )
				$id = $data['*'];
			elseif ( is_string ( $data['*'] ) )
				$id = $data['*'];
		}

		if ( is_null ( $id ) )
			$id = sha1 ( serialize ( $this->data_element ( $data_key ) ) );

		return $id;
	}


}
