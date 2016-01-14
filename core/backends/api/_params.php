<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  API: Generic: Params.
#
#  Will utilize the Info module or pre-set standard settings.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic/params.php' );



abstract class API_Params extends Params
{

	# ----- Internal ----- #

	protected function log ( $msg, $loglevel = LL_INFO, $preface = "params: " )
	{
		if ( $preface == "params: " )
			$preface = $this->modulename() . " " . $preface;
		return parent::log ( $msg, $loglevel, $preface );
	}


	# ----- Implementing paramdesc() ----- #

	protected function paraminfo_paramdesc ()
	{
		$module = $this->info->param_anymodule (
			$this->modulename(), $this->rootmodulename() );

		$paramdesc = array ( 'params' => array() );

		if ( isset ( $module['prefix'] ) )
			$paramdesc['prefix'] = $module['prefix'];
		else
			$paramdesc['prefix'] = "";

		if ( isset ( $module['generator'] ) )
			$paramdesc['generator'] = true;
		if ( isset ( $module['readrights'] ) )
			$paramdesc['readrights'] = true;
		if ( isset ( $module['writerights'] ) )
			$paramdesc['writerights'] = true;
		if ( isset ( $module['mustbeposted'] ) )
			$paramdesc['mustbeposted'] = true;

		if ( isset ( $module['parameters'] ) )
			foreach ( $module['parameters'] as &$infoparam )
			{
				$param = array();
				if ( isset ( $infoparam['type'] ) )
					$param['type'] = $infoparam['type'];
				else
					$param['type'] = "string"; // seems to be the default

				if ( isset ( $infoparam['multi'] ) )
					$param['multi'] = true;
				if ( isset ( $infoparam['required'] ) )
					$param['required'] = true;
				if ( isset ( $infoparam['allows_duplicates'] ) )
					$param['allows_duplicates'] = true;
				if ( isset ( $infoparam['default'] ) )
					$param['default'] = $infoparam['default'];
				if ( isset ( $infoparam['limit'] ) )
					if ( $this->info->user_right_in_rights ( 'apihighlimits' ) )
					{
						if ( isset ( $infoparam['highlimit'] ) )
							$param['limit'] = $infoparam['highlimit'];
						else
							$param['limit'] = $infoparam['limit'];
					}
					else
					{
						$param['limit'] = $infoparam['limit'];
					}
				if ( isset ( $infoparam['max'] ) )
					if ( $this->info->user_right_in_rights ( 'apihighlimits' ) )
						$param['max'] = $infoparam['highmax'];
					else
						$param['max'] = $infoparam['max'];

				$paramdesc['params'][$infoparam['name']] = $param;
			}

		return $paramdesc;
	}

	protected function paramdesc ()
	{
		if ( is_object ( $this->info ) )
		{
			if ( $this->info->param_info_isset() )
			{
				if ( $this->info->param_anymodule_exists (
					$this->modulename(), $this->rootmodulename() ) )
				{
					return $this->paraminfo_paramdesc();
				}
				else
				{
					if ( ! $this->settings['lax_mode'] )
						return NULL;
				}
			}
			$mwverno = $this->info->wiki_version_number();
			return $this->hardcoded_paramdesc ( $mwverno );
		}
		else
		{
			return $this->hardcoded_paramdesc ( NULL );
		}
	}


	protected function set_param_dir ( $startname, $endname, $dirname )
	{  // todo! use it or lose it! and use directions from desc!
		if ( isset ( $this->params[$startname] ) ||
			isset ( $this->params[$endname] ) )
		{
			if ( ! isset ( $this->params[$dirname] ) )
			{
				if ( ! isset ( $this->params[$startname] ) ||
					! isset ( $this->params[$endname] ) )
				{
					$this->params[$dirname] = "newer";
				}
				elseif ( $this->params[$startname] < $this->params[$endname] )
				{
					$this->params[$dirname] = "newer";
				}
				else
				{
					$this->params[$dirname] = "older";
				}
			}

		}
	}


	# ----- Overriding ----- #

	public function set_param ( $name, $value )
	{
		if ( is_string ( $value ) && ( strpos ( $value, '|' ) !== false ) &&
			$this->split_paramvalue_on_pipes ( $name )
		)
			$value = explode ( '|', $value );

		return parent::set_param ( $name, $value );
	}


	# ----- Overridable ----- #


	protected function split_paramvalue_on_pipes ( $name )
	{
		return true;
	}


	# ----- Public ----- #


	# --- Obtaining params info --- #

	public function prefix ()
	{
		if ( is_null ( $this->paramdesc ) )
			return NULL;
		return $this->paramdesc['prefix'];
	}


	# ----- Abstract ----- #

	abstract protected function rootmodulename();

	abstract public function modulename ();

	abstract protected function hardcoded_paramdesc ( $mw_version_number );


}
