<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - File permissions checker class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/match_items_any.php' );


class Checker_FilePermissions extends Checker_MatchItems_Any
{


	# ----- Constructor ----- #


	function __construct ( $permissions )
	{
		if ( ! is_array ( $permissions ) )
			$permissions = array ( $permissions );
		parent::__construct ( $permissions, array ( $this, "check_element" ) );
	}


	# ----- Instantiating ----- #


	public function check ( $element )
	{
		$permstring  = ( ( $element & 0x0100 ) ? 'r' : '-' ) .
			( ( $element & 0x0080 ) ? 'w' : '-' );
		$permstring .= ( ( $element & 0x0040 )
			? ( ( $element & 0x0800 ) ? 's' : 'x' )
			: ( ( $element & 0x0800 ) ? 'S' : '-' ) );
		$permstring .= ( ( $element & 0x0020 ) ? 'r' : '-' ) .
			( ( $element & 0x0010 ) ? 'w' : '-' );
		$permstring .= ( ( $element & 0x0008 )
			? ( ( $element & 0x0400 ) ? 's' : 'x' )
			: ( ( $element & 0x0400 ) ? 'S' : '-' ) );
		$permstring .= ( ( $element & 0x0004 ) ? 'r' : '-' ) .
			( ( $element & 0x0002 ) ? 'w' : '-' );
		$permstring .= ( ( $element & 0x0001 )
			? ( ( $element & 0x0200 ) ? 't' : 'x' )
			: ( ( $element & 0x0200 ) ? 'T' : '-' ) );

		return parent::check ( $permstring );
	}


	protected function check_element ( $permstring, $permission )
	{
		if ( substr ( $permission, 0, 1 ) == '!' )
		{
			$permission = substr ( $permission, 1 );
			$invert = true;
		}
		else
		{
			$invert = false;
		}

		$checked = true;
		for ( $i = 0; $i < 10; $i++ )
		{
			$p = substr ( $permission, $i, 1 );
			if ( $p == '?' )
				continue;

			$f = substr ( $permstring, $i, 1 );
			if ( $p != $f )
			{
				$checked = false;
				break;
			}
		}

		if ( $invert )
			return ( ! $checked );
		else
			return $checked;

	}


}
