<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Blanket include for all generator query feeder modules.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/allcategories.php' );
require_once ( dirname ( __FILE__ ) . '/allimages.php' );
require_once ( dirname ( __FILE__ ) . '/alllinks.php' );
require_once ( dirname ( __FILE__ ) . '/allpages.php' );
require_once ( dirname ( __FILE__ ) . '/alltransclusions.php' );
require_once ( dirname ( __FILE__ ) . '/backlinks.php' );
require_once ( dirname ( __FILE__ ) . '/categories.php' );
require_once ( dirname ( __FILE__ ) . '/categorymembers.php' );
require_once ( dirname ( __FILE__ ) . '/duplicatefiles.php' );
require_once ( dirname ( __FILE__ ) . '/embeddedin.php' );
require_once ( dirname ( __FILE__ ) . '/exturlusage.php' );
require_once ( dirname ( __FILE__ ) . '/by_name.php' );
require_once ( dirname ( __FILE__ ) . '/images.php' );
require_once ( dirname ( __FILE__ ) . '/imageusage.php' );
require_once ( dirname ( __FILE__ ) . '/iwbacklinks.php' );
require_once ( dirname ( __FILE__ ) . '/langbacklinks.php' );
require_once ( dirname ( __FILE__ ) . '/links.php' );
require_once ( dirname ( __FILE__ ) . '/protectedtitles.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_ancientpages.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_brokenredirects.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_deadendpages.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_disambiguations.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_doubleredirects.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_fewestrevisions.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_listredirects.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_lonelypages.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_longpages.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_mostcategories.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_mostimages.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_mostlinkedcategories.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_mostlinked.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_mostlinkedtemplates.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_mostrevisions.php' );
require_once ( dirname ( __FILE__ ) . '/querypage.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_shortpages.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_uncategorizedcategories.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_uncategorizedimages.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_uncategorizedtemplates.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_unusedcategories.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_unusedimages.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_unusedtemplates.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_unwatchedpages.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_wantedcategories.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_wantedfiles.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_wantedpages.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_wantedtemplates.php' );
require_once ( dirname ( __FILE__ ) . '/querypage_withoutinterwiki.php' );
require_once ( dirname ( __FILE__ ) . '/random.php' );
require_once ( dirname ( __FILE__ ) . '/recentchanges.php' );
require_once ( dirname ( __FILE__ ) . '/search.php' );
require_once ( dirname ( __FILE__ ) . '/templates.php' );
require_once ( dirname ( __FILE__ ) . '/watchlist.php' );
require_once ( dirname ( __FILE__ ) . '/watchlistraw.php' );
