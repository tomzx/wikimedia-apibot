<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Imageusage Generator feeder class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Feeder_Query_Generator_Imageusage extends Feeder_Query_Generator
{


	public $set_title_from_signal_data = true;


	# ----- Overriding ----- #


	protected function set_slot_params_from_signal ( &$signal )
	{
		if ( ( $this->set_title_from_signal_data ) &&
			( $signal instanceof LineSignal_Data ) )
		{
			$title = $signal->data_title ( $this->default_data_key );
			if ( ! is_null ( $title ) )
				$this->title = $title;
		}

		return parent::set_slot_params_from_signal ( $signal );
	}


	# ----- Instantiating ----- #

	protected function query ( &$core )
	{
		require_once ( dirname ( __FILE__ ) .
			'/../../../../../core/queries/generator/imageusage.php' );
		return new Query_Generator_Imageusage ( $core );
	}


}
