<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Frequently used checker callback functions.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


function check_callback__equals ( $element, $item )
{
	return ( $element == $item );
}

function check_callback__exact_equals ( $element, $item )
{
	return ( $element === $item );
}


function check_callback__in_array ( $element_array, $item )
{
	return in_array ( $item, $element_array );
}

function check_callback__not_in_array ( $element_array, $item )
{
	return ! in_array ( $item, $element_array );
}


function check_callback__match_regex ( $element, $regex )
{
	return (bool) preg_match ( $regex, $element );
}

function check_callback__match_regex_withneg ( $element, $regex )
{
	if ( substr ( $regex, 0, 1 ) == '!' )
	{
		$regex = substr ( $regex, 1 );
		return ( ! preg_match ( $regex, $element ) );
	}
	else
	{
		return preg_match ( $regex, $element );
	}
}


