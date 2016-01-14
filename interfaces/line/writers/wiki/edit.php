<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Edit (submit) page Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Writer_Wiki_Edit extends Writer_Wiki_Generic
{

	public $summary      = NULL;

	public $section      = NULL;
	public $sectiontitle = NULL;

	public $is_bot       = NULL;
	public $is_minor     = NULL;
	public $watch        = NULL;
	public $recreate     = NULL;
	public $createonly   = NULL;
	public $nocreate     = NULL;


	# ----- Instantiated ----- #


	protected function signal_log_slot_name ()
	{
		return "Edit";
	}


	protected function task_paramnames ()
	{
		return array_merge (
			parent::task_paramnames(),
			array (
				"summary",
				"section",
				"sectiontitle",
				"is_bot",
				"is_minor",
				"watch",
				"recreate",
				"createonly",
				"nocreate",
			)
		);
	}


	# ----- Overriding ----- #


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		if ( ! isset ( $this->summary ) || is_null ( $this->summary ) )
			$this->summary = $this->changes_summary ( $signal );

		$params = $this->get_task_params();

		$params['page'] = $signal->data_element ( $this->default_data_key );

		require_once ( dirname ( __FILE__ ) .
			'/../../../../core/tasks//edit.php' );

		$task = new Task_Edit ( $this->core );
		$result = $task->go ( $params );

		$this->set_jobdata ( $result );

		return $result;
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'summary' );
		$this->_get_param ( $params, 'section' );
		$this->_get_param ( $params, 'sectiontitle' );
		$this->_get_param ( $params, 'is_bot' );
		$this->_get_param ( $params, 'is_minor' );
		$this->_get_param ( $params, 'watch' );
		$this->_get_param ( $params, 'recreate' );
		$this->_get_param ( $params, 'createonly' );
		$this->_get_param ( $params, 'nocreate' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'summary' );
		$this->_set_param ( $params, 'section' );
		$this->_set_param ( $params, 'sectiontitle' );
		$this->_set_param ( $params, 'is_bot' );
		$this->_set_param ( $params, 'is_minor' );
		$this->_set_param ( $params, 'watch' );
		$this->_set_param ( $params, 'recreate' );
		$this->_set_param ( $params, 'createonly' );
		$this->_set_param ( $params, 'nocreate' );

		return parent::set_params ( $params );
	}


}
