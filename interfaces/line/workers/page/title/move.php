<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Generic Move (change) Title Worker class
#  (implements auto-move page functionality)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic.php' );
require_once ( dirname ( __FILE__ ) . '/../../../writers/wiki/move_title.php' );



class Worker_Page_Move extends Worker_Page
{


	# ----- Tools ----- #


	protected function do_rule ( $title, $rule )
	{
		if ( array_key_exists ( 'string', $rule ) )
			$title = str_replace ( $rule['string'], $rule['with'], $title );
		elseif ( array_key_exists ( 'regex', $rule ) )
			$title = preg_replace ( $rule['regex'], $rule['with'], $title );
		else
			$this->log ( "MoveTitle: bad title change rule", LL_ERROR );
		return $title;
	}


	# ----- Overriding ----- #


	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Title.Move";
	}


	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );
		if ( $result === false )
			return false;

		$title = $signal->data_title ( $this->default_data_key );
		if ( is_null ( $title ) )
		{
			$this->log ( "Could not determine the title from a signal!", LL_ERROR );
			return false;
		}

		if ( ! empty ( $this->tasks ) )
		{
			$to_title = $title;

			if ( array_key_exists ( 'with', $this->tasks ) )
				$to_title = $this->do_rule ( $to_title, $this->tasks );
			else
				foreach ( $this->tasks as $task )
					$to_title = $this->do_rule ( $to_title, $task );

			$result = ( $title !== $to_title );
			if ( $result )
			{
				$signal->set_param ( 'writer', 'to_title', $to_title );

				$this->add_changes ( "Moving [[$title]] as [[$to_title]]" );
			}
		}
		else
		{
			$this->log ( "Title [[" . $title . "]] is not to be changed", LL_DEBUG );
		}

		$this->set_jobdata ( $result );

		return $result;
	}


	# ----- Implemented ----- #


	protected function autosubmitter ( &$signal )
	{
		return new Writer_Wiki_Move ( $this->core );
	}


}
