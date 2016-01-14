<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line: Worker: Set signal param from data value class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_SetParam_FromDataValue extends Worker_SetParam_FromData
{


	public $from_sublevels = NULL;


	# ----- Overriding ----- #


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'from_sublevels' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'from_sublevels' );

		return parent::set_params ( $params );
	}


	# ----- Implemented ----- #


	protected function new_paramvalue ( &$signal )
	{
		$element = $signal->data_element ( $this->default_data_key );

		$from_sublevels = $this->from_sublevels;

		while ( ! empty ( $from_sublevels ) )
		{
			$from_sublevel = array_shift ( $from_sublevels );

			if ( is_array ( $element ) )
				if ( isset ( $element[$from_sublevel] ) )
					$element = $element[$from_sublevel];
				else
					return NULL;

			elseif ( is_object ( $element ) )
				if ( isset ( $element->$from_sublevel ) )
					$element = $element->$from_sublevel;
				else
					return NULL;

			else
				return NULL;

		}

		return $element;
	}


}
