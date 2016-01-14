<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Mains: Hooks.
#
#  The Apibot code defaults for a hook are set at creating the object with it.
#  However, hooks for it can be set before that, while scanning extensions etc.
#  That is why there are separate hooks and defaults.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


class Hooks
{

	protected $settings;

	protected $hooks = array();
	public $default_hook;


	# ----- Constructor ----- #

	function __construct ( $settings = array() )
	{
		$this->settings = $settings;  // no settings defined, by now
	}


	# ----- Tools ----- #


	protected function no_default_hook_error ( $hookname )
	{
		die ( "Internal error (no default hook for $hookname) - exitting!\n" );
	}


	# ----- Hooks ----- #


	public function get ( $hookname )
	{
		if ( isset ( $this->hooks[$hookname] ) )
			return $this->hooks[$hookname];
		elseif ( isset ( $this->default_hook ) )
			return $this->default_hook;
		else
			return $this->no_default_hook_error ( $hookname );
	}


	public function set ( $hookname, $hook )
	{
		if ( ! is_callable ( $hook ) )
			die ( "A hook being set for $hookname is not callable - exitting!\n" );

		if ( isset ( $this->hooks[$hookname] ) )
			$old_hook = $this->hooks[$hookname];
		else
			$old_hook = array ( $this, "call_default" );

		$this->hooks[$hookname] = $hook;

		return $old_hook;
	}


	public function del ( $hookname )
	{
		if ( isset ( $this->hooks[$hookname] ) )
		{
			$hook = $this->hooks[$hookname];
			unset ( $this->hooks[$hookname] );
		}
		else
		{
			$hook = NULL;
		}

		return $hook;
	}


	# This function is to be used from the Apibot code, not from the hooking code!
	# When hooking, set your hook through get(); it will return the old hook.
	# Preserve that and call it from your hook to ensure passthrough functionality.
	# This way, it will eventually go to the Apibot default hook, which you need.
	# (That is why you must call this from Apibot code, and give it default hook.)
	public function call ( $hookname, $default_hook, $hook_object,
		$p1 = NULL, $p2 = NULL, $p3 = NULL, $p4 = NULL, $p5 = NULL,
		$p6 = NULL, $p7 = NULL, $p8 = NULL, $p9 = NULL, $p10 = NULL )
	{
		# Hooks are class-specific, and there can be multiple objects from a class,
		# every object with its own properties etc,
		# so an object-specific default hook must be set at every call 
		$this->default_hook = $default_hook;

		if ( isset ( $this->hooks[$hookname] ) )
			$result = call_user_func (
				$this->hooks[$hookname],
				$hook_object,
				$p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10
			);
		else
			$result = $this->call_default (
				$hook_object,
				$p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10 );

		unset ( $this->default_hook );

		return $result;
	}


	public function call_default ( $hook_object,
		$p1 = NULL, $p2 = NULL, $p3 = NULL, $p4 = NULL, $p5 = NULL,
		$p6 = NULL, $p7 = NULL, $p8 = NULL, $p9 = NULL, $p10 = NULL )

	{
		if ( isset ( $this->default_hook ) )
			return call_user_func ( $this->default_hook, $hook_object,
				$p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10 );
		else
			return $this->no_default_hook_error ( $hookname );
	}


}
