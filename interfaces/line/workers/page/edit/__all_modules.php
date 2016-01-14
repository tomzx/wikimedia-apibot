<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Blanket include for all worker edit modules.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/add_categories.php' );
require_once ( dirname ( __FILE__ ) . '/add_categories_strings.php' );
require_once ( dirname ( __FILE__ ) . '/add_filelinks.php' );
require_once ( dirname ( __FILE__ ) . '/add_interwikis.php' );
require_once ( dirname ( __FILE__ ) . '/add_interwikis_strings.php' );
require_once ( dirname ( __FILE__ ) . '/add_templates.php' );
require_once ( dirname ( __FILE__ ) . '/add_wikilinks.php' );
require_once ( dirname ( __FILE__ ) . '/append.php' );
require_once ( dirname ( __FILE__ ) . '/delete_categories.php' );
require_once ( dirname ( __FILE__ ) . '/delete_categories_sortkeys.php' );
require_once ( dirname ( __FILE__ ) . '/delete_filelinks.php' );
require_once ( dirname ( __FILE__ ) . '/delete_interwikis.php' );
require_once ( dirname ( __FILE__ ) . '/delete.php' );
require_once ( dirname ( __FILE__ ) . '/delete_templates.php' );
require_once ( dirname ( __FILE__ ) . '/delete_wikilinks.php' );
require_once ( dirname ( __FILE__ ) . '/insert.php' );
require_once ( dirname ( __FILE__ ) . '/prepend.php' );
require_once ( dirname ( __FILE__ ) . '/replace_categories.php' );
require_once ( dirname ( __FILE__ ) . '/replace_filelinks_captions.php' );
require_once ( dirname ( __FILE__ ) . '/replace_filelinks_names.php' );
require_once ( dirname ( __FILE__ ) . '/replace_filelinks_params.php' );
require_once ( dirname ( __FILE__ ) . '/replace_filelinks.php' );
require_once ( dirname ( __FILE__ ) . '/replace_interwikis.php' );
require_once ( dirname ( __FILE__ ) . '/replace_regexes.php' );
require_once ( dirname ( __FILE__ ) . '/replace_strings.php' );
require_once ( dirname ( __FILE__ ) . '/replace_templates_names.php' );
require_once ( dirname ( __FILE__ ) . '/replace_templates_paramnames.php' );
require_once ( dirname ( __FILE__ ) . '/replace_templates_paramvalues.php' );
require_once ( dirname ( __FILE__ ) . '/replace_templates.php' );
require_once ( dirname ( __FILE__ ) . '/replace_wikilinks.php' );
require_once ( dirname ( __FILE__ ) . '/replace_wikilinks_targets.php' );
require_once ( dirname ( __FILE__ ) . '/replace_wikilinks_texts.php' );
require_once ( dirname ( __FILE__ ) . '/set_categories_sortkeys.php' );
require_once ( dirname ( __FILE__ ) . '/set_interwikis_targets.php' );
require_once ( dirname ( __FILE__ ) . '/unlink_wikilinks.php' );
require_once ( dirname ( __FILE__ ) . '/wikilink_regexes.php' );
require_once ( dirname ( __FILE__ ) . '/wikilink_texts.php' );
