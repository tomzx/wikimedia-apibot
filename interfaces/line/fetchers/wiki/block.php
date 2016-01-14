<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Block (by id) fetcher class
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );


class Fetcher_Wiki_Block extends Fetcher_Wiki
{

	public $prop;


	# ----- Overriding ----- #


	protected function get_params ()
	{
		$params = parent::get_params();

		$this->_get_param ( $params, 'prop' );

		return $params;
	}

	protected function set_params ( $params )
	{
		$this->_set_param ( $params, 'prop' );

		return parent::set_params ( $params );
	}


	# ----- Instantiated ----- #

	protected function signal_log_slot_name ()
	{
		return parent::signal_log_slot_name() . ".Block";
	}


	protected function process_data ( &$signal )
	{
		parent::process_data ( $signal );

		$id = $signal->data_blockid ( $this->default_data_key );
		if ( is_null ( $this->id ) )
			return false;

		$params = array (
			'prop' => $this->prop,
			'ids' => $id,
		);

		require_once ( dirname ( __FILE__ ) .
			'/../../../../core/queries/list/blocks.php' );

		$query = new Query_List_Blocks ( $this->core );
		$block = $query->go ( $params );

		$result = $this->set_fetched_data ( $signal, $block );

		$this->set_jobdata ( $result,
			array ( 'id' => $id, 'prop' => $this->prop ) );

		return $result;
	}


	protected function element_typemark ()
	{
		return "block";
	}


}
