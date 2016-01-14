<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Write elements in CSV format file Writer generic class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


// todo! fputcsv() generates disfunctional crap of a CSV.
// better make a function generating proper CSV line string and write it!


require_once ( dirname ( __FILE__ ) . '/_generic_plain.php' );
require_once ( dirname ( __FILE__ ) . '/../../../../core/data/_generic.php' );



class Writer_File_CSV extends Writer_FileRecords_Plain
{

	public $delimiter = ',';
	public $enclosure = '"';

	public $fields;


	# ----- Instantiating ----- #

	protected function element_record ( &$signal )
	{
		$element = $signal->data_element ( $this->default_data_key );
		return $this->element_record_array ( $element );
	}


	# ----- Overriding ----- #

	protected function write_record ( $fp, $record )
	{
		if ( isset ( $this->fields ) && is_array ( $this->fields ) )
		{
			$selected = array();
			foreach ( $this->fields as $field )
				if ( isset ( $record[$field] ) )
					$selected[] = $record[$field];
			$record = $selected;
		}
		return @fputcsv ( $this->fp, $record, $this->delimiter, $this->enclosure );
	}


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".CSV";
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'delimiter' );
		$this->_get_param ( $params, 'enclosure' );
		$this->_get_param ( $params, 'fields' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'delimiter' );
		$this->_set_param ( $params, 'enclosure' );
		$this->_set_param ( $params, 'fields' );

		return parent::set_params ( $params );
	}


	# ----- Overridable ----- #

	protected function element_record_array ( $element )
	{
		if ( is_array ( $element ) )
		{
			return $element;
		}
		elseif ( is_string ( $element ) || is_numeric ( $element ) )
		{
			return array ( $element );
		}
		elseif ( is_object ( $element ) )
		{
			if ( $element instanceof Dataobject )
			{
				return $element->data();
			}
			else
			{
				return get_object_vars ( $element );
			}
		}
		else
		{
			$this->log ( get_class ( $this ) .
				": Cannot determine what data to write in a CSV file", LL_ERROR );
			return NULL;
		}
	}


}
