<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Blanket include for all File url filter modules.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/regexes_exist_all.php' );
require_once ( dirname ( __FILE__ ) . '/regexes_exist_any.php' );
require_once ( dirname ( __FILE__ ) . '/regexes_notexist_all.php' );
require_once ( dirname ( __FILE__ ) . '/regexes_notexist_any.php' );

require_once ( dirname ( __FILE__ ) . '/size_diap.php' );
