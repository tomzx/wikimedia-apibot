<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Modules: Generic.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_params.php' );



abstract class API_Module extends API_Params
{

	# --- Service objects --- #

	protected $exchanger;

	# earlier versions of MW dont add 'mustbeposted' key in paramdescs
	protected $mustbeposted = false; // to be overridden where true

	# --- Public variables --- #

	public $data;  // the data returned by the exchanger after call


	# ----- Constructor ----- #

	function __construct ( $exchanger, $info, $hooks, $settings )
	{
		$this->exchanger = $exchanger;
		try
		{
			parent::__construct ( $hooks, $info, $settings );
		}
		catch ( ApibotException $exception )
		{
			throw new ApibotException_InternalError (
				"Module " . $this->modulename . " appears to be unsupported by this wiki"
			);
		}
	}


	# ----- Tools ----- #


	# ----- Implemented ----- #

	protected function rootmodulename ()
	{
		return "modules";
	}


	# ----- Data servicing ----- #

	protected function elements_count ( $array )
	{
		if ( is_array ( $array ) )
			return count ( $array );
		else
			return NULL;
	}

	protected function elements_keys ( $array )
	{
		if ( is_array ( $array ) )
			return array_keys ( $array );
		else
			return NULL;
	}

	protected function element ( $array, $element_key )
	{
		if ( is_array ( $array ) && isset ( $array[$element_key] ) )
			return $array[$element_key];
		else
			return NULL;
	}


	protected function array_or_element ( $array, $element_key = NULL )
	{
		if ( is_null ( $element_key ) )
			return $array;
		else
			return $this->element ( $area, $element_key );
	}


	# ----- Data parsing ----- #

	public function data_areas_keys ()
	{
		return $this->elements_keys ( $this->data );
	}

	public function data_area ( $key )
	{
		return $this->element ( $this->data, $key );
	}

	public function results ()
	{
		return $this->data_area ( $this->modulename() );
	}

	public function errors ()
	{
		return $this->data_area ( 'error' );
	}

	public function warnings ()
	{
		return $this->data_area ( 'warnings' );
	}

	public function limits ()
	{
		return $this->data_area ( 'limits' );
	}


	# --- Splicing results --- #

	public function data_area_elements_count ( $area_key )
	{
		return $this->elements_count ( $this->data_area ( $area_key ) );
	}

	public function data_area_elements_keys ( $area_key )
	{
		return $this->elements_keys ( $this->data_area ( $area_key ) );
	}

	public function data_area_element ( $area_key, $element_key )
	{
		return $this->element ( $this->data_area ( $area_key ), $element_key );
	}


	public function data_area_first_element ( $area_key )
	{
		if ( isset ( $this->data[$area_key] ) && is_array ( $this->data[$area_key] ) )
			return reset ( $this->data[$area_key] );
		else
			return NULL;
	}

	public function data_area_next_element ( $area_key )
	{
		if ( isset ( $this->data[$area_key] ) && is_array ( $this->data[$area_key] ) )
			return next ( $this->data[$area_key] );
		else
			return NULL;
	}

	public function data_area_last_element ( $area_key )
	{
		if ( isset ( $this->data[$area_key] ) && is_array ( $this->data[$area_key] ) )
			return end ( $this->data[$area_key] );
		else
			return NULL;
	}


	public function results_elements_count ()
	{
		return $this->elements_count ( $this->results() );
	}

	public function results_elements_keys ()
	{
		return $this->elements_keys ( $this->results() );
	}

	public function results_element ( $key )
	{
		return $this->element ( $this->data[$this->modulename()], $key );
	}


	# ----- Xfer servicing ----- #

	public function nohooks__xfer ( $hook_object )
	{
		$params = $this->params();
		$params['action'] = $this->modulename();

		$files = $this->files();

		$mustbeposted = $this->mustbeposted();

		$result = $this->exchanger->xfer ( $params, $files, $mustbeposted );

		$this->data = $this->data();

		return $result;
	}


	public function xfer ()
	{
		return $this->hooks->call (
			get_class ( $this ) . '::xfer',
			array ( $this, 'nohooks__xfer' ),
			$this
		);
	}


	protected function data ()  // overridable on need
	{
		$data_ptr = &$this->exchanger->data;
		return $data_ptr;
	}


}
