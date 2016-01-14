<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - File Type filter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic_file_stat.php' );


abstract class Filter_DirEntry_File_TypeCheck extends
	Filter_DirEntry_File_WithStat
{

	protected $filetypes = array (
		's'      => 0xc000,
		'socket' => 0xc000,
		'l'      => 0xa000,
		'link'   => 0xa000,
		'-'      => 0x8000,
		'file'   => 0x8000,
		'b'      => 0x6000,
		'block'  => 0x6000,
		'd'      => 0x4000,
		'dir'    => 0x4000,
		'c'      => 0x2000,
		'char'   => 0x2000,
		'p'      => 0x1000,
		'fifo'   => 0x1000,
	);


	# ----- Tools ----- #

	protected function types ( $types )
	{
		if ( ! is_array ( $types ) )
			$types = array ( $types );

		foreach ( $types as &$type )
			if ( ! is_numeric ( $type ) )
				$type = $this->filetypes[$type];

		return array_unique ( $types );
	}


	# ----- Instantiating ----- #

	protected function slotname_preface ()
	{
		return "File_Type";
	}


	# ----- Overriding ----- #

	protected function element_to_check ( &$signal )
	{
		$data = parent::element_to_check ( $signal );
		if ( isset ( $data['mode'] ) )
			return ( $data['mode'] & 0xf000 );
		else
			return NULL;
	}


}
