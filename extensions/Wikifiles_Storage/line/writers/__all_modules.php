<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Blanket include for all wikifiles storage writer modules.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/adopt.php' );
require_once ( dirname ( __FILE__ ) . '/append.php' );
require_once ( dirname ( __FILE__ ) . '/delete.php' );
require_once ( dirname ( __FILE__ ) . '/modify.php' );
require_once ( dirname ( __FILE__ ) . '/rename.php' );
