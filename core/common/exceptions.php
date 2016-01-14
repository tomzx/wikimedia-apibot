<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Mains: Exceptions.
#
#  Generic bot exceptions classes. Derive your own from these.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


# ---------------------------------------------------------------------------- #
# --                     Exception ranges and advices                       -- #
# ---------------------------------------------------------------------------- #


define ( 'AEX_RANGE_BOT'   , 0 );  // The entire bot is affected.
define ( 'AEX_RANGE_TASK'  , 1 );  // The current bot task is affected.
define ( 'AEX_RANGE_ACCESS', 2 );  // The current access attempt is affected.

define ( 'AEX_ADVICE_ABORT' , 0 ); // Abort affected range without cleanup.
define ( 'AEX_ADVICE_CLOSE' , 1 ); // Abort affected range, cleanup is OK.
define ( 'AEX_ADVICE_RETRY' , 2 ); // Retry the call, possibly after a wait.
define ( 'AEX_ADVICE_IGNORE', 3 ); // Ignoring is OK (but you may still retry).



# ---------------------------------------------------------------------------- #
# --                          Generic exceptions                            -- #
# ---------------------------------------------------------------------------- #


class ApibotException extends Exception
{

	public $range;   // range of the error
	public $advice;  // action that is advised to be taken
	                 //   (typically the smallest acceptable for this error)
	public $code;    // error unique code (a string)
	public $info;    // error user-friendly description

	function __construct ( $range, $advice, $code, $info ) {
		$this->range  = $range;
		$this->advice = $advice;
		$this->code   = $code;
		$this->info   = $info;
	}

}



class ApibotException_Bot extends ApibotException
{
	function __construct ( $advice, $code, $info )
	{
		parent::__construct ( AEX_RANGE_BOT, $advice, $code, $info );
	}
}

class ApibotException_BotAbort extends ApibotException_Bot
{
	function __construct ( $code, $info )
	{
		parent::__construct ( AEX_ADVICE_ABORT, $code, $info );
	}
}

class ApibotException_BotClose extends ApibotException_Bot
{
	function __construct ( $code, $info )
	{
		parent::__construct ( AEX_ADVICE_CLOSE, $code, $info );
	}
}

class ApibotException_BotRetry extends ApibotException_Bot
{
	function __construct ( $code, $info )
	{
		parent::__construct ( AEX_ADVICE_RETRY, $code, $info );
	}
}

class ApibotException_BotIgnore extends ApibotException_Bot
{
	function __construct ( $code, $info )
	{
		parent::__construct ( AEX_ADVICE_IGNORE, $code, $info );
	}
}



class ApibotException_Task extends ApibotException
{
	function __construct ( $advice, $code, $info )
	{
		parent::__construct ( AEX_RANGE_TASK, $advice, $code, $info );
	}
}

class ApibotException_TaskAbort extends ApibotException_Task
{
	function __construct ( $code, $info )
	{
		parent::__construct ( AEX_ADVICE_ABORT, $code, $info );
	}
}

class ApibotException_TaskClose extends ApibotException_Task
{
	function __construct ( $code, $info )
	{
		parent::__construct ( AEX_ADVICE_CLOSE, $code, $info );
	}
}

class ApibotException_TaskRetry extends ApibotException_Task
{
	function __construct ( $code, $info )
	{
		parent::__construct ( AEX_ADVICE_RETRY, $code, $info );
	}
}

class ApibotException_TaskIgnore extends ApibotException_Task
{
	function __construct ( $code, $info )
	{
		parent::__construct ( AEX_ADVICE_IGNORE, $code, $info );
	}
}



class ApibotException_Access extends ApibotException
{
	function __construct ( $advice, $code, $info )
	{
		parent::__construct ( AEX_RANGE_ACCESS, $advice, $code, $info );
	}
}

class ApibotException_AccessAbort extends ApibotException_Access
{
	function __construct ( $code, $info )
	{
		parent::__construct ( AEX_ADVICE_ABORT, $code, $info );
	}
}

class ApibotException_AccessClose extends ApibotException_Access
{
	function __construct ( $code, $info )
	{
		parent::__construct ( AEX_ADVICE_CLOSE, $code, $info );
	}
}

class ApibotException_AccessRetry extends ApibotException_Access
{
	function __construct ( $code, $info )
	{
		parent::__construct ( AEX_ADVICE_RETRY, $code, $info );
	}
}

class ApibotException_AccessIgnore extends ApibotException_Access
{
	function __construct ( $code, $info )
	{
		parent::__construct ( AEX_ADVICE_IGNORE, $code, $info );
	}
}


# ---------------------------------------------------------------------------- #
# --                           Common exceptions                            -- #
# ---------------------------------------------------------------------------- #


class ApibotException_InternalError extends ApibotException_BotAbort
{
	function __construct ( $info )
	{
		parent::__construct (
			"internalerror",
			$info
		);
	}
}

class ApibotException_CantReadFile extends ApibotException_TaskClose
{
	function __construct ( $filename, $range = AEX_RANGE_TASK,
		$advice = AEX_ADVICE_CLOSE )
	{
		parent::__construct (
			"cantreadfile",
			"Could not read file: " . $filename
		);
		$this->range  = $range;
		$this->advice = $advice;
	}
}

class ApibotException_CantWriteFile extends ApibotException_TaskClose
{
	function __construct ( $filename, $range = AEX_RANGE_TASK,
		$advice = AEX_ADVICE_CLOSE )
	{
		parent::__construct (
			"cantwritefile",
			"Could not write file: " . $filename
		);
		$this->range  = $range;
		$this->advice = $advice;
	}
}
