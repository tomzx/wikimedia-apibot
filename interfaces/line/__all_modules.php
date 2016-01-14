<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Blanket include for all line modules.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/feeders/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/fetchers/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/filters/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/workers/__all_modules.php' );
require_once ( dirname ( __FILE__ ) . '/writers/__all_modules.php' );


# ----- Scanning extensions for line modules catch_alls ----- #


$extensions_path = dirname ( __FILE__ ) . '/../../extensions';
$extensions = @scandir ( $extensions_path );

foreach ( $extensions as $extension )
{
	if ( substr ( $extension, 0, 1 ) == '.' )
		continue;

	$extension_path = $extensions_path . '/' . $extension;

	if ( @is_dir ( $extension_path ) )
	{
		if ( @file_exists ( $extension_path . '/line/__all_modules.php' ) )
			require_once ( $extension_path . '/line/__all_modules.php' );

		elseif ( @file_exists ( $extension_path . '/__all_modules.php' ) )
			require_once ( $extension_path . '/__all_modules.php' );
	}
}
