<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Blanket include for all filter modules.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/general/__all_modules.php' );

require_once ( dirname ( __FILE__ ) . '/dir_entry/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/feed/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/file/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/page/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/unique/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/user/__all_modules.php' );

