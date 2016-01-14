<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Blanket include for all page text filter modules.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/categories_exist_all.php' );
require_once ( dirname ( __FILE__ ) . '/categories_exist_any.php' );
require_once ( dirname ( __FILE__ ) . '/categories_notexist_all.php' );
require_once ( dirname ( __FILE__ ) . '/categories_notexist_any.php' );
require_once ( dirname ( __FILE__ ) . '/filelinks_exist_all.php' );
require_once ( dirname ( __FILE__ ) . '/filelinks_exist_any.php' );
require_once ( dirname ( __FILE__ ) . '/filelinks_notexist_all.php' );
require_once ( dirname ( __FILE__ ) . '/filelinks_notexist_any.php' );
require_once ( dirname ( __FILE__ ) . '/interwikis_exist_all.php' );
require_once ( dirname ( __FILE__ ) . '/interwikis_exist_any.php' );
require_once ( dirname ( __FILE__ ) . '/interwikis_notexist_all.php' );
require_once ( dirname ( __FILE__ ) . '/interwikis_notexist_any.php' );
require_once ( dirname ( __FILE__ ) . '/redirect_false.php' );
require_once ( dirname ( __FILE__ ) . '/redirect_true.php' );
require_once ( dirname ( __FILE__ ) . '/regexes_exist_all.php' );
require_once ( dirname ( __FILE__ ) . '/regexes_exist_any.php' );
require_once ( dirname ( __FILE__ ) . '/regexes_notexist_all.php' );
require_once ( dirname ( __FILE__ ) . '/regexes_notexist_any.php' );
require_once ( dirname ( __FILE__ ) . '/strings_exist_all.php' );
require_once ( dirname ( __FILE__ ) . '/strings_exist_any.php' );
require_once ( dirname ( __FILE__ ) . '/strings_notexist_all.php' );
require_once ( dirname ( __FILE__ ) . '/strings_notexist_any.php' );
require_once ( dirname ( __FILE__ ) . '/templates_exist_all.php' );
require_once ( dirname ( __FILE__ ) . '/templates_exist_any.php' );
require_once ( dirname ( __FILE__ ) . '/templates_notexist_all.php' );
require_once ( dirname ( __FILE__ ) . '/templates_notexist_any.php' );
require_once ( dirname ( __FILE__ ) . '/wikilinks_exist_all.php' );
require_once ( dirname ( __FILE__ ) . '/wikilinks_exist_any.php' );
require_once ( dirname ( __FILE__ ) . '/wikilinks_notexist_all.php' );
require_once ( dirname ( __FILE__ ) . '/wikilinks_notexist_any.php' );
