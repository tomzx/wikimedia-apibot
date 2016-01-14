<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Lib: Get / Set / Unset subelements of complex array/object hierarchies.
#
#  If setting value to a sub of an element that does not exist, this element
#  will be auto-created as an empty array.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


function get_subelement ( $compound, $subspath )
{
	if ( empty ( $subspath ) )
		return $compound;

	$level = array_shift ( $subspath );

	if ( is_array ( $compound ) )
		if ( array_key_exists ( $level, $compound ) )
			return get_subelement ( $compound[$level], $subspath );
		else
			return NULL;

	elseif ( is_object ( $compound ) )
		if ( property_exists ( $level, $compound ) )
			return get_subelement ( $compound->$level, $subspath );
		else
			return NULL;

	else
		return NULL;
}


function set_subelement ( $compound, $subspath, $value )
{
	if ( empty ( $subspath ) && ! ( $subspath === 0 ) )
		return $value;

	if ( is_array ( $subspath ) )
		$level = array_shift ( $subspath );
	else
	{
		$level = $subspath;
		$subspath = NULL;
	}

	if ( is_array ( $compound ) )
	{
		if ( ! array_key_exists ( $level, $compound ) )
			$compound[$level] = array();

		$result = set_subelement ( $compound[$level], $subspath, $value );
		if ( is_null ( $result ) )
			return NULL;
		else
			$compound[$level] = $result;
	}

	elseif ( is_object ( $compound ) )
	{
		if ( ! property_exists ( $level, $compound ) )
			$compound->$level = array();

		$result = set_subelement ( $compound->$level, $subspath, $value );
		if ( is_null ( $result ) )
			return NULL;
		else
			$compound->$level = $result;
	}

	else
		return NULL;

	return $compound;
}


function unset_subelement ( $compound, $subspath )
{
	if ( empty ( $subspath ) && ! ( $subspath === 0 ) )
		return NULL;

	if ( is_array ( $subspath ) )
		$level = array_shift ( $subspath );
	else
	{
		$level = $subspath;
		$subspath = NULL;
	}

	if ( is_array ( $compound ) &&array_key_exists ( $level, $compound ) )
		if ( empty ( $subspath ) )
			unset ( $compound[$level] );
		else
			$compound[$level] = unset_subelement ( $compound[$level], $subspath );

	elseif ( is_object ( $compound ) && property_exists ( $level, $compound ) )
		if ( empty ( $subspath ) )
			unset ( $compound->$level );
		else
			$compound->$level = unset_subelement ( $compound[$level], $subspath );

	return $compound;
}
