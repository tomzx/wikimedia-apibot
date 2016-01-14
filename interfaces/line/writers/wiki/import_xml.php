<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Import pages XML Writer class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Writer_Wiki_ImportXML extends Writer_Wiki_Generic
{

	public $summary = NULL;


	# ----- Instantiated ----- #


	protected function signal_log_slot_name ()
	{
		return "Import.XML";
	}


	protected function task_paramnames ()
	{
		return array_merge (
			parent::task_paramnames(),
			array (
				"summary",
			)
		);
	}


	# ----- Overriding ----- #


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$params = $this->get_task_params();

		$element = $signal->data_element ( $this->default_data_key );
		if ( is_array ( $element ) )
			$params['xml'] = ( isset ( $element['xml'] ) ? $element['xml'] : NULL );
		else
			$params['xml'] = $element;

		require_once ( dirname ( __FILE__ ) .
			'/../../../../core/tasks//import_xml.php' );

		$task = new Task_ImportXML ( $this->core );
		$result = $task->go ( $params );

		$this->set_jobdata ( $result );

		return $result;
	}


}
