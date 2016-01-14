<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Deletedrevs List feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Feeder_Query_List_Deletedrevs extends Feeder_Query_List
{

	public $iterate_pages = true;


	# ----- Instantiating ----- #

	protected function data_type ()
	{
		if ( $this->iterate_pages )
			return "array/page";  // with 'revisions' key => array
		else
			return "array/revision";
	}


	protected function query ( &$core )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../../../../core/queries/list/deletedrevs.php' );
		return new Query_List_Deletedrevs ( $core );
	}


	protected function query_data_key () {
		return $this->queryname();
	}


	# ----- Overriding ----- #

	public function query_feed_element ( $element, $element_key )
	{
		foreach ( $element['revisions'] as $key => $revision )
		{
			$revision['ns'] = $element['ns'];
			$revision['title'] = $element['title'];

			if ( ! parent::query_feed_element ( $revision, $key ) )
				return false;
		}
		return true;
	}


	# ----- Setting params ----- #
	  // deletedrevs module can utilise also additional query params

	public function set_titles ( $titles )
	{
		return $this->query->set_titles ( $titles );
	}


	public function set_generator ( $name )
	{
		return $this->query->set_generator ( $name );
	}

	public function is_generator_paramname_ok ( $generator, $name )
	{
		return $this->query->is_generator_paramname_ok ( $generator, $name );
	}

	public function is_generator_param_under_limit ( $generator, $name )
	{
		return $this->query->is_generator_param_under_limit ( $generator, $name );
	}

	public function is_generator_paramvalue_ok ( $generator, $name, $value )
	{
		return $this->query->is_generator_paramvalue_ok ( $generator, $name, $value );
	}

	public function get_generator_param ( $name )
	{
		return $this->query->get_generator_param ( $name );
	}

	public function set_generator_param ( $name, $value )
	{
		return $this->query->set_generator_param ( $name, $value );
	}

	public function set_generator_params ( $params_array )
	{
		return $this->query->set_generator_params ( $params_array );
	}


}
