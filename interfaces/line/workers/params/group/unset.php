<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal paramgroup unsetter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_Paramgroup_Unset extends Worker_Paramgroup
{

	protected $old_value;


	# ----- Implemented ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Unset";
	}


	# ----- Overriding ----- #

	protected function process_data ( &$signal )
	{
		$result = parent::process_data ( $signal );

		$this->old_value = $signal->paramgroup ( $this->group );
		$this->group = $this->group ( $signal );

		$signal->unset_paramgroup ( $this->group );

		$extra_params = array (
			'group' => $this->group,
			'old_value' => $this->old_value,
		);

		$this->set_jobdata ( $result, $extra_params, array ( "group" ) );

		return $result;
	}


}
