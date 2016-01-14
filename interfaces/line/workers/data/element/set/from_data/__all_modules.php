<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Blanket include for all signal data element modification modules.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/checksums/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/coders/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/math/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/string/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/transform/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/wikivalues/__all_modules.php' );

require_once ( dirname ( __FILE__ ) . '/copy.php' );
require_once ( dirname ( __FILE__ ) . '/move.php' );

