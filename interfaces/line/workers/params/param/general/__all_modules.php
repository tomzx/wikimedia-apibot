<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Blanket include for all signal custom paarmeter change modules.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/copy.php' );
require_once ( dirname ( __FILE__ ) . '/rename.php' );
require_once ( dirname ( __FILE__ ) . '/set.php' );
require_once ( dirname ( __FILE__ ) . '/swap.php' );
require_once ( dirname ( __FILE__ ) . '/unset.php' );
