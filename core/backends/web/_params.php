<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  MW API module parameters - generic class.
#
#  Will utilize the Info module or pre-set standard settings.
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/../_generic/params.php' );



abstract class Web_Params extends Params
{

	# ----- Implementing paramdesc() ----- #

	protected function paramdesc ()
	{
		if ( is_object ( $this->info ) )
		{
			if ( $this->info->param_info_isset() )
			{
				$mwverno = $this->info->wiki_version_number();
				return $this->hardcoded_paramdesc ( $mwverno );
			}
		}
		return $this->hardcoded_paramdesc ( NULL );
	}


	# ----- Abstract ----- #

	abstract protected function hardcoded_paramdesc ( $mw_version_number );


}
