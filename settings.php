<?php
#
#  This is a sample Bot Settings file.
#
#  The examples from the Apibot wiki rely on a structure like this.
#
# ---------------------------------------------------------------------------- #

# Standard bot settings. See the comments.

require_once ( dirname ( __FILE__ ) . '/core/common/log.php' );  // provides the loglevels


$bot_settings = array (

	# General settings.
	'environment' => array (
		'timezone' => "UTC",
		'memory_limit' => "512M",
	),

	# Behavour while setting different parameters (query etc.)
	'setparams' => array (
		'lax_mode' => false,  // true - will accept all set parameters; false - will check the paramdesc
	),

	# Miscellaneous paths.
	'paths' => array (
		'info'     => dirname ( __FILE__ ) . "/../_info",      // Keep here info files (obsoleted and superceded by 'info_path' in 'infostore')
		'identity' => dirname ( __FILE__ ) . "/../_identity",  // Keep here identity files (obsoleted and superceded by 'identity_path' in 'infostore')
	),

	# Settings for the Browser module.
	'browser' => array (
		'agent'        => "Mozilla/5.0 (Apibot Browser)",  // User-Agent (best make it individual for your bot)
		'http_version' => "HTTP/1.1",  // Report this version
		'http_user'    => NULL,  // HTTP (not MediaWiki!) authentication user (if any)
		'http_pass'    => NULL,  // HTTP authentication password
		'conn_timeout' => 120,   // Connection timeout
		'max_get_len'  => 2048,  // If a GET string will exceed this length, use POST instead
		'content_type' => array (  // Use these types. (Currently all GETs are considered text, and all POSTs binary.)
			'text'   => "application/x-www-form-urlencoded",
			'binary' => "multipart/form-data",
		),
		'mime_boundary'   => "Apibot-Browser-$1", // for multipart/form-data ($1 is replaced by a random string)
		'use_compression' => true, // Compress transfered data where possible
		'max_retries'     => 1,    // maximal number of retries allowed (browser-level)
		'speed_limits'    => array (  // Transfer speed (average) limits
			'total' => PHP_INT_MAX,  // Both upload and download
			'DL'    => PHP_INT_MAX,  // Download only
			'UL'    => PHP_INT_MAX,  // Upload only
		),
		'dump_level'      => 0,  // Dump to stdout transferred data: 0 - none, 1 - all data
	),

	# Settings for the exchanger modules.
	'exchanger' => array (
		'settings' => array (
			'api' => array (
				'max_retries' => 5,  // how many times to retry on link errors
				'retry_wait'  => 1,  // a delay factor (retry No. is multiplied by it before each retry)
			),
			'web' => array (
			),
			'dump_level' => 0,  // Dump to stdout exchanged data: 0 - none, 1 - all data
		),
		'defaults' => array (
			'api' => array (
				'format'  => "json",  // currently json and php are supported
				'maxlag'  => 5,
			),
		),
	),

	# Settings for the Log module
	'log' => array (
		'loglevel' => LL_DEBUG,  // LL_PANIC, LL_ERROR, LL_WARNING, LL_INFO, LL_DEBUG - log from least to most
		'logfile'  => "test.log",  // log filename
		'echo_log' => true,   // echo log to the stdout too?
		'html_log' => false,  // log messages as HTML?
		'levelprefs' => array (  // mark the loglines with these characters according to the line loglevel
			LL_PANIC   => '!',
			LL_ERROR   => '#',
			LL_WARNING => '=',
			LL_INFO    => '+',
			LL_DEBUG   => '-'
		),
	),

	# Settings for the Identity module
	'identity' => array (
		'always_login'   => false,  // true - always login, false - use cookies where available
		'login_attempts' => 5,   // try to login up to that many times before giving up
	),

	# Settings for the Infostore module
	'infostore' => array (
		'info_path'     => dirname ( __FILE__ ) . "/../_info",      // Keep here info files
		'identity_path' => dirname ( __FILE__ ) . "/../_identity",  // Keep here identity files
	),

	# Settings for the Info module
	'info' => array (
		'infotypes' => array (
			'general' => array (  // general info settings
				'fetch' => "always",  // always, never, if_unknown, if_older_than ('days'), on_newversion, on_newrevision
			),
			'site' => array (  // siteinfo settings
				'fetch' => "on_newrevision",
			),
			'param' => array (  // paraminfo settings
				'fetch' => "on_newrevision",
			),
			'user' => array (  // userinfo settings
				'fetch' => "on_newrevision",
			),
			'allmessages' => array (  // allmessages info settings
				'fetch' => "on_newrevision",
			),
			'filerepoinfo' => array (  // file repo info settings
				'fetch' => "on_newrevision",
			),
			'globaluser' => array (  // globaluser info settings
				'fetch' => "on_newrevision",
			),
		),
	),

	# Settings for the Actions modules.
	# Can be defined in several levels:
	# - array ( modulename => array ( type => array ( backend => array (...) ) ) )
	# - array ( modulename => array ( type => array (...) ) ) (lower priority)
	# - array ( type => array (...) ) (lowest priority)
	# Modulenames are the names of the Action modules (that is, the API modules).
	# Types are 'defaults' (default params) and 'settings' (default settings).
	# Backends are 'api' and 'web' (data is specific for this backend only).
	'actions' => array (
		'block' => array (
			'defaults' => array (
				'anononly'  => true,
				'nocreate'  => true,
				'autoblock' => true,
				'noemail'   => true,
				'expiry'    => "never",
				'reason'    => NULL,
			),
		),
		'delete' => array (
			'defaults' => array (
				'watch'  => NULL,
				'reason' => NULL,
			),
		),
		'edit' => array (
			'defaults' => array (
				'text'         => NULL,
				'prependtext'  => NULL,
				'appendtext'   => NULL,
				'section'      => NULL,
				'sectiontitle' => NULL,
				'undo'         => NULL,
				'undoafter'    => NULL,
				'minor'        => true,
				'bot'          => true,
				'add_md5'      => true,
				'recreate'     => NULL,
				'createonly'   => NULL,
				'nocreate'     => NULL,
				'redirect'     => false,
				'watchlist'    => NULL,
				'summary'      => NULL,
			),
		),
		'emailuser' => array (
			'defaults' => array (
				'user'    => NULL,
				'text'    => NULL,
				'subject' => NULL,
				'ccme'    => false,
			),
		),
		'expandtemplates' => array (
			'defaults' => array (
				'text'  => NULL,
				'title' => NULL,
			),
		),
		'help' => array (
		),
		'import' => array (
			'defaults' => array (
				'interwikisource' => NULL,
				'templates'       => false,
				'fullhistory'     => NULL,
				'namespace'       => NULL,
				'summary'         => NULL,
			),
		),
		'login' => array (
		),
		'logout' => array (
		),
		'move' => array (
			'defaults' => array (
				'movetalk'     => true,
				'movesubpages' => true,
				'noredirect'   => NULL,
				'watch'        => NULL,
				'reason'       => NULL,
			),
		),
		'paraminfo' => array (
		),
		'parse' => array (
			'defaults' => array (
				'text'    => NULL,
				'title'   => NULL,
				'prop'    => NULL,
				'pst'     => NULL,
				'uselang' => NULL,
			),
		),
		'patrol' => array (
		),
		'protect' => array (
			'defaults' => array (
				'protections' => array (
					'edit'  => "sysop",
					'move'  => "sysop",
				),
				'expiry'  => array (
					"never",
					"never",
				),
				'cascade' => true,
				'reason'  => NULL,
			),
		),
		'purge' => array (
		),
		'query' => array (
			'defaults' => array (
				'redirect'      => NULL,
				'indexpageids'  => false,
				'export'        => false,
				'exportnowrap'  => false,
				'converttitles' => false,
				'iwurl'         => false,
			),
		),
		'rollback' => array (
			'defaults' => array (
				'title'   => NULL,
				'user'    => NULL,
				'summary' => NULL,
				'markbot' => true,
			),
		),
		'unblock' => array (
			'defaults' => array (
				'reason' => NULL,
			),
		),
		'undelete' => array (
			'defaults' => array (
				'title'  => NULL,
				'reason' => NULL,
			),
		),
		'upload' => array (
			'defaults' => array (
				'comment'        => NULL,
				'text'           => NULL,
				'watch'          => NULL,
				'ignorewarnings' => false,
			),
		),
		'userrights' => array (
			'defaults' => array (
				'add'    => NULL,
				'remove' => NULL,
				'reason' => NULL,
			),
		),
		'watch' => array (
			'defaults' => array (
				'unwatch' => NULL,
			),
		),
	),

	# Settings for the general tasks modules.
	'tasks' => array (
		'backends' => array ( "api", "web" ),  // which backends to use and in what order
		'fetch_editable' => array (
			'fetch_objects' => true,  // default - true
		),
		'fetch_title' => array (
		),
	),

);
