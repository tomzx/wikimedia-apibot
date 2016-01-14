<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Line framework - Diapazone checker class
#  (Works with numbers, numeric timestamps, ASCII strings etc.)
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_check.php' );


class Checker_Diap extends Checker
{

# $params argument to the constructor must be array ( 'min' => ..., 'max' => ... )


	# ----- Instantiating ----- #


	public function check ( $element )
	{
		if ( is_null ( $this->params['min'] ) )
			return $element <= $this->params['max'];
		elseif ( is_null ( $this->params['max'] ) )
			return $element >= $this->params['min'];
		else
			return ( $this->params['min'] <= $this->params['max'] )
				? ( ( $element >= $this->params['min'] ) &&
					( $element <= $this->params['max'] ) )
				: ( ( $element <= $this->params['min'] ) ||
					( $element >= $this->params['max'] ) );
	}


	public function job_name ()
	{
		return "Diap";
	}


}
