<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic Page processing Worker class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../writers/wiki/move_title.php' );



abstract class Worker_Page extends Worker
{

	public $tasks;

	public $autosubmit;


	# ----- Constructor ----- #

	function __construct ( $core, $tasks = array(), $autosubmit = true )
	{
		$this->tasks = $tasks;
		$this->autosubmit = $autosubmit;
		parent::__construct ( $core );
	}


	# ----- Implemented ----- #

	protected function signal_log_slot_name ()
	{
		return "Page";
	}


	# ----- Overriding ----- #

	protected function process_signal ( &$signal )
	{
		if ( ! $this->is_linked() && $this->autosubmit )
		{
			$writer = $this->autosubmitter ( $signal );
			$this->log ( get_class ( $this ) . ": Not linked with anything -" .
				" auto-linking with a spawned " . get_class ( $writer ) . " object...",
				LL_WARNING );
			$this->link_with ( $writer );
		}

		return parent::process_signal ( $signal );
	}


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'tasks' );
		$this->_get_param ( $params, 'autosubmit' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'tasks' );
		$this->_set_param ( $params, 'autosubmit' );

		return parent::set_params ( $params );
	}


	# ----- Abstract ----- #


	abstract protected function autosubmitter ( &$signal );


}
