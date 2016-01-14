<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Blanket include for all page filter modules.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/comment/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/new/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/ns/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/pageid/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/parentid/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/redirect/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/revid/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/size/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/text/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/timestamp/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/title/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/user/__all_modules.php' );
