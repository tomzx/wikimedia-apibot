<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Blanket include for all wiki info feeder modules.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/dbrepllag.php' );
require_once ( dirname ( __FILE__ ) . '/extensions.php' );
require_once ( dirname ( __FILE__ ) . '/extensiontags.php' );
require_once ( dirname ( __FILE__ ) . '/fileextensions.php' );
require_once ( dirname ( __FILE__ ) . '/by_name.php' );
require_once ( dirname ( __FILE__ ) . '/functionhooks.php' );
require_once ( dirname ( __FILE__ ) . '/interwikis.php' );
require_once ( dirname ( __FILE__ ) . '/magicwords.php' );
require_once ( dirname ( __FILE__ ) . '/namespacealiases.php' );
require_once ( dirname ( __FILE__ ) . '/namespace_allnames.php' );
require_once ( dirname ( __FILE__ ) . '/namespaces_allnames.php' );
require_once ( dirname ( __FILE__ ) . '/namespaces_canonicalnames.php' );
require_once ( dirname ( __FILE__ ) . '/namespaces_ids.php' );
require_once ( dirname ( __FILE__ ) . '/namespaces_names.php' );
require_once ( dirname ( __FILE__ ) . '/namespaces.php' );
require_once ( dirname ( __FILE__ ) . '/rightsinfo.php' );
require_once ( dirname ( __FILE__ ) . '/showhooks.php' );
require_once ( dirname ( __FILE__ ) . '/skins.php' );
require_once ( dirname ( __FILE__ ) . '/specialpagealiases.php' );
require_once ( dirname ( __FILE__ ) . '/statistics.php' );
require_once ( dirname ( __FILE__ ) . '/user_acceptlang.php' );
require_once ( dirname ( __FILE__ ) . '/user_changeablegroups.php' );
require_once ( dirname ( __FILE__ ) . '/user_groups.php' );
require_once ( dirname ( __FILE__ ) . '/usergroups.php' );
require_once ( dirname ( __FILE__ ) . '/user_implicitgroups.php' );
require_once ( dirname ( __FILE__ ) . '/user_options.php' );
require_once ( dirname ( __FILE__ ) . '/user_ratelimits.php' );
require_once ( dirname ( __FILE__ ) . '/user_rights.php' );
