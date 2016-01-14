<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic wiki-directed Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );


abstract class Writer_Wiki_Generic extends Writer
{


	# ----- Overridden ----- #


	protected function get_params ()
	{
		return array_merge ( parent::get_params(), $this->get_task_params() );
	}


	protected function set_params ( $params )
	{
		$this->set_task_params ( $params );
		parent::set_params ( $params );
	}


	# ----- New ----- #


	protected function get_task_params ()
	{
		$params = array();

		foreach ( $this->task_paramnames() as $name )
			$this->_get_param ( $params, $name );

		return $params;
	}


	protected function set_task_params ( $params )
	{
		foreach ( $this->task_paramnames() as $name )
			$this->_set_param ( $params, $name );
	}


	protected function task_paramnames ()
	{
		return array();
	}


}
