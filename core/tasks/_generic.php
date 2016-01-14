<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Task: Generic.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #



abstract class Task
{

	protected $core;
	protected $settings;


	# ----- Constructor ----- #

	function __construct ( $core )
	{
		$this->core = $core;
		$this->settings = $this->default_settings();
	}


	# ----- Tools ----- #

	protected function log ( $message, $loglevel = LL_INFO, $preface = "" )
	{
		return $this->core->log ( $message, $loglevel, $preface );
	}


	protected function default_settings ()
	{
		$settings = $this->core->settings->get ( 'tasks', $this->task_name() );
		if ( empty ( $settings ) )
			$settings = array();

		if ( ! isset ( $settings['backends'] ) )
		{
			$settings['backends'] =
				$this->core->settings->get ( 'tasks', 'backends' );

			if ( empty ( $settings['backends'] ) )
				$settings['backends'] = $this->supported_backends();

			if ( ! is_array ( $settings['backends'] ) )
				$settings['backends'] = array ( $settings['backends'] );
		}

		return $settings;
	}


	# ----- Overridable ----- #


	protected function api_task ()
	{
		return NULL;
	}

	protected function web_task ()
	{
		return NULL;
	}


	protected function postprocess_result ( $result )
	{
		return $result;
	}


	# ----- Entry point ----- #


	public function nohooks__go ( $hook_object, $params = array() )
	{
		$result = NULL;

		foreach ( $this->settings['backends'] as $backend )
		{

			switch ( $backend )
			{

				case "api" :
					$task = $this->api_task();
					if ( is_object ( $task ) && $task->is_operable() )
						break;

				case "web" :
					$task = $this->web_task();
					if ( is_object ( $task ) && $task->is_operable() )
						break;

				default :
					$this->log ( get_class ( $this ) . ": Unknown backend: $backend",
						LL_ERROR );
					$task = NULL;

			}

			if ( is_object ( $task ) )
			{
				$result = $this->postprocess_result ( $task->go ( $params ) );
				break;
			}

		}

		return $result;
	}


	public function go ( $params = array() )
	{
		return $this->core->hooks->call (
			get_class ( $this ) . '::go',
			array ( $this, 'nohooks__go' ),
			$this, $params
		);
	}


	# ----- Abstract ----- #

	abstract protected function task_name ();

	abstract protected function supported_backends ();


}
