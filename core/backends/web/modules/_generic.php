<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Web: Modules: Generic.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_params.php' );



abstract class Web_Module extends Web_Params
{

	# --- Service objects --- #

	protected $exchanger;

	protected $mustbeposted = false; // to be overridden where true

	# --- Public variables --- #

	public $data;  // the data returned by the exchanger after call


	# ----- Constructor ----- #

	function __construct ( $exchanger, $info, $hooks, $settings )
	{
		parent::__construct ( $hooks, $info, $settings );
		$this->exchanger = $exchanger;
	}


	# ----- Overriding ----- #

	public function mustbeposted ()
	{
		return ( parent::mustbeposted() || $this->mustbeposted );
	}


	# ----- Xfer ----- #

	public function xfer ()
	{
		$params = $this->params();
		$files = $this->files();
		$mustbeposted = $this->mustbeposted();

		$result = $this->exchanger->xfer ( $params, $files, $mustbeposted );

		$this->data = $this->data();

		return $result;
	}


	protected function data ()  // overridable!
	{
		$data_ptr = &$this->exchanger->data;
		return $data_ptr;
	}


}
