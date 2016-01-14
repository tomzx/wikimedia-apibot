<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Signal Data Revid parameter setter class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Worker_SetParam_FromRevid extends Worker_SetParam_FromWikivalues
{

	# ----- Overriding ----- #

	protected function new_paramvalue ( &$signal )
	{
		return $signal->data_revid ( $this->default_data_key );
	}

}