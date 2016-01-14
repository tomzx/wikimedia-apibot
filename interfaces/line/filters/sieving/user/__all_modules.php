<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Blanket include for all user filter modules.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/blockedby/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/blockexpiry/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/blockreason/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/editcount/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/gender/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/groups/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/implicitgroups/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/name/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/registration/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/userid/__all_modules.php' );
