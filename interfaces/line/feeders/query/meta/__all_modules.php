<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Blanket include for all meta query feeder modules.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/allmessages.php' );
require_once ( dirname ( __FILE__ ) . '/filerepoinfo.php' );
require_once ( dirname ( __FILE__ ) . '/dbrepllag.php' );
require_once ( dirname ( __FILE__ ) . '/extensions.php' );
require_once ( dirname ( __FILE__ ) . '/fileextensions.php' );
require_once ( dirname ( __FILE__ ) . '/by_name.php' );
require_once ( dirname ( __FILE__ ) . '/general.php' );
require_once ( dirname ( __FILE__ ) . '/interwikimap.php' );
require_once ( dirname ( __FILE__ ) . '/languages.php' );
require_once ( dirname ( __FILE__ ) . '/magicwords.php' );
require_once ( dirname ( __FILE__ ) . '/namespacealiases.php' );
require_once ( dirname ( __FILE__ ) . '/namespaces.php' );
require_once ( dirname ( __FILE__ ) . '/rightsinfo.php' );
require_once ( dirname ( __FILE__ ) . '/siteinfo.php' );
require_once ( dirname ( __FILE__ ) . '/specialpagealiases.php' );
require_once ( dirname ( __FILE__ ) . '/statistics.php' );
require_once ( dirname ( __FILE__ ) . '/usergroups.php' );
require_once ( dirname ( __FILE__ ) . '/userinfo.php' );
