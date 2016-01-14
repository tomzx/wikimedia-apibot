<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Data objects classes: Page revision
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Revision extends Dataobject
{

	public function __get ( $name )
	{
		if ( $name == 'is_minor' )
			return $this->__isset ( 'minor' );

		if ( ( $name == 'content' ) || ( $name == 'text' ) )
			$name = '*';
		return parent::__get ( $name );
	}

	public function __set ( $name, $value )
	{
		if ( $name == 'is_minor' )
			if ( $value )
			{
				$this->__set ( 'minor' );
			}
			else
			{
				$this->__unset ( 'minor' );
			}

		if ( ( $name == 'content' ) || ( $name == 'text' ) )
			$name = '*';
		parent::__set ( $name, $value );
	}

	public function __isset ( $name )
	{
		if ( ( $name == 'content' ) || ( $name == 'text' ) )
			$name = '*';
		return parent::__isset ( $name );
	}

	public function __unset ( $name )
	{
		if ( ( $name == 'content' ) || ( $name == 'text' ) )
			$name = '*';
		parent::__unset ( $name );
	}

}
