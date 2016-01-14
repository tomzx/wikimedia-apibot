<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Blanket include for all wiki writer modules.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/block.php' );
require_once ( dirname ( __FILE__ ) . '/createaccount.php' );
require_once ( dirname ( __FILE__ ) . '/delete_pageid.php' );
require_once ( dirname ( __FILE__ ) . '/delete_title.php' );
require_once ( dirname ( __FILE__ ) . '/edit.php' );
require_once ( dirname ( __FILE__ ) . '/emailuser.php' );
require_once ( dirname ( __FILE__ ) . '/import_interwiki.php' );
require_once ( dirname ( __FILE__ ) . '/import_xml.php' );
require_once ( dirname ( __FILE__ ) . '/move_pageid.php' );
require_once ( dirname ( __FILE__ ) . '/move_title.php' );
require_once ( dirname ( __FILE__ ) . '/patrol.php' );
require_once ( dirname ( __FILE__ ) . '/protect.php' );
require_once ( dirname ( __FILE__ ) . '/purge.php' );
require_once ( dirname ( __FILE__ ) . '/rollback.php' );
require_once ( dirname ( __FILE__ ) . '/semiprotect.php' );
require_once ( dirname ( __FILE__ ) . '/unblock.php' );
require_once ( dirname ( __FILE__ ) . '/undelete.php' );
require_once ( dirname ( __FILE__ ) . '/undo.php' );
require_once ( dirname ( __FILE__ ) . '/unprotect.php' );
require_once ( dirname ( __FILE__ ) . '/unwatch.php' );
require_once ( dirname ( __FILE__ ) . '/upload_file.php' );
require_once ( dirname ( __FILE__ ) . '/upload_url_async.php' );
require_once ( dirname ( __FILE__ ) . '/upload_url.php' );
require_once ( dirname ( __FILE__ ) . '/userrights.php' );
require_once ( dirname ( __FILE__ ) . '/watch.php' );
