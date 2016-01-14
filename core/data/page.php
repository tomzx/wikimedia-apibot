<?php
#
#  A MediaWiki bot - used for automated tasks on sites powered by MediaWiki.
#  Common: Data objects classes: Page
#
#  Author: Grigor Gatchev <grigor at gatchev dot info>
#  Licensed under GNU Affero Public License, version 3.0 or any later.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/_generic.php' );



class Page extends Dataobject
{

	public $text_sections;  // temporary storage for sections split (by method)


	# ---- Constructor ----- #

	function __construct ( $core, $title, $text = NULL )
	{
		if ( is_string ( $title ) )  // not a data array
			$title = array ( 'title' => $title, 'text' => $text );

		parent::__construct ( $core, $title );
	}


	# ----- Magic properties overrude ----- #

	public function __get ( $name )
	{
		if ( $name == 'redirect' )
			return isset ( $this->data['redirect'] );
		if ( $name == 'new' )
			return isset ( $this->data['redirect'] );
		if ( $name == 'content' )
			$name = 'text';

		if ( ( $name == 'text' ) &&
			! isset ( $this->data['text'] ) && isset ( $this->data['text_sections'] ) )
			$this->from_sections();

		if ( ( $name == 'text_sections' ) &&
			! isset ( $this->data['text_sections'] ) && isset ( $this->data['text'] ) )
			$this->to_sections();

		return parent::__get ( $name );
	}

	public function __set ( $name, $value )
	{
		if ( $name == 'redirect' )
			if ( $value )
				$this->data['redirect'] = "";
			else
				unset ( $this->data['redirect'] );
		if ( $name == 'new' )
			if ( $value )
				$this->data['new'] = "";
			else
				unset ( $this->data['new'] );
		if ( $name == 'content' )
			$name = 'text';
		parent::__set ( $name, $value );
	}

	public function __isset ( $name )
	{
		if ( $name == 'redirect' )
			return true;  // emulated property
		if ( $name == 'new' )
			return true;  // emulated property
		if ( $name == 'content' )
			$name = 'text';
		return parent::__isset ( $name );
	}


	# ---------- Info ---------- #

	public function is_redirect ()
	{
		return (bool) preg_match ( '/^\s*' .
			$this->core->info->magicword_barsepnames ( 'redirect' ) .
			'\s*\[\[([^\]]+)\]\]\s*$/Ui', $this->text );
	}

	public function redirects_to ()
	{
		if ( preg_match ( '/^\s*' .
			$this->core->info->magicword_barsepnames ( 'redirect' ) .
			'\s*\[\[([^\]]+)\]\]\s*$/Ui', $this->text, $matches ) )
		{
			return $matches[2];
		}
		else
		{
			return NULL;
		}
	}

	public function is_main ()
	{
		return $this->core->info->is_main_page ( $this->title );
	}

	public function is_talk ()
	{
		return $this->core->info->is_talk_page ( $this->title );
	}

	public function is_special ()
	{
		return $this->core->info->is_special_page ( $this->title );
	}

	public function talk_page_title ()
	{
		if ( $this->is_talk() )
			return $this->title;
		return $this->core->info->talk_page_title ( $this->title );
	}

	public function main_page_title ()
	{
		if ( $this->is_main() )
			return $this->title;
		return $this->core->info->main_page_title ( $this->title );
	}


	# ---------- Edit ---------- #

	# ----- Tools ----- #

	protected function page_element_pieces ( $element, $opening, $closing )
	{
		$regex = '/^\s*' . preg_quote ( $opening ) . '(.+)' .
			preg_quote ( $closing ) . '\s*$/uSs';

		if ( ! preg_match ( $regex, $element, $matches ) )
			return NULL;

		$pieces = preg_split ( '/\|/uS', $matches[1] );

		$params = array();
		$temp = "";
		foreach ( $pieces as $piece )
		{
			if ( ! empty ( $temp ) )
				$temp .= '|';
			$temp .= $piece;
			if ( ( substr_count ( $temp, '[[' ) == substr_count ( $temp, ']]' ) ) &&
				( substr_count ( $temp, '{{' ) == substr_count ( $temp, '}}' ) ) )
			{
				$params[] = $temp;
				$temp = "";
			}
		}
		return $params;
	}


	# ----- General ----- #

	public function regex_exists ( $regex )
	{
		if ( substr ( $regex, 0, 1 ) == '!' )
		{
			$regex = substr ( $regex, 1 );
			return ( ! preg_match ( $regex, $this->text ) );
		}
		else
		{
			return preg_match ( $regex, $this->text );
		}
	}

	public function string_exists ( $string )
	{
		return ( strpos ( $this->text, $string ) !== false );
	}


	public function replace_regex ( $regex, $with, $limit = -1 )
	{
		$this->text = preg_replace ( $regex, $with, $this->text, $limit, $count );
		return $count;
	}

	public function replace_string ( $string, $with )
	{
		$this->text = str_replace ( $string, $with, $this->text, $count );
		return $count;
	}

	public function append ( $text )
	{
		$this->text .= $text;
		return true;
	}

	public function prepend ( $text )
	{
		$this->text = $text . $this->text;
		return true;
	}

	public function insert ( $text,
		$regexpart_before = NULL, $regexpart_after = NULL, $limit = -1 )
	{

		if ( ! is_null ( $regexpart_before ) )
			$regexpart_before = "(?<=" . $regexpart_before . ")";
		if ( ! is_null ( $regexpart_after ) )
			$regexpart_after = "(?=" . $regexpart_after . ")";

		$regexpart = $regexpart_before . $regexpart_after;
		if ( empty ( $regexpart ) )
			$regexpart = "$";  // append at the end

		return $this->replace_regex ( '/' . $regexpart . '/u', $text, $limit );
	}

	public function delete ( $regex, $limit = -1 )
	{
		return $this->replace_regex ( $regex, "", $limit );
	}


	# ----- Wikilinks ----- #

	# --- Tools --- #

	# Matches array keys:
	# 0 - the entire link, 1 - leaging colon, 2 - wiki + colon, 3 - wiki only,
	# 4 - title (namespace:name), 5 - namespace + colon, 6 - namespace,
	# 7 - name, 8 - sharp + anchor, 9 - anchor, 10 - all params (bars + texts),
	# 11 - last param bar + text, 12 - last param text, 13 - a newline after it.
	protected function wikilink_regexmatch ( $colon = NULL, $wiki = NULL,
		$namespace = NULL, $name = NULL, $anchor = NULL, $text = NULL,
		$newline_after = false )
	{

		if ( is_array ( $colon ) )
			return $this->wikilink_regexmatch ( $colon['colon'], $colon['wiki'],
				$colon['namespace'], $colon['name'], $colon['anchor'], $colon['text'],
				$newline_after );

		$regex = '\[\[\h*';

		if ( is_null ( $colon ) )
		{
			$regex .= '(\:?)\h*';
		}
		elseif ( $colon === true )
		{
			$regex .= '(\:)\h*';
		}
		else
		{
			$regex .= '()';
		}

		if ( is_null ( $wiki ) )
		{
			$regex .= '(([^\:\#\|\]]+)\:)?\h*';
		}
		elseif ( $wiki === "" )
		{
			$regex .= '(())';
		}
		elseif ( $wiki === "*" )
		{
			$regex .= '(' . $this->core->info->interwikis_prefixesregex() .
				'\h*\:)\h*';
		}
		else
		{
			$regex .= '((' . $wiki . ')\h*\:)\h*';
		}

		if ( is_null ( $namespace ) )
		{
			$regex .= '((([^\:\#\|\]]+)\:)?\h*';
		}
		elseif ( $namespace === "" )
		{
			$regex .= "((())";
		}
		elseif ( $namespace === "*" )
		{
			$regex .= '((' . $this->core->info->namespaces_namesregex() .
				'\h*\:)\h*';
		}
		else
		{
			$regex .= '(((' . $namespace . ')\h*\:)\h*';
		}

		if ( is_null ( $name ) )
		{
			$regex .= '([^\#\|\]]+))';
		}
		elseif ( $name === "" )
		{
			$regex .= "())";
		}
		else
		{
			$regex .= '(' . $name . '))';
		}

		if ( is_null ( $anchor ) )
		{
			$regex .= '(\#([^\|\]]*))?';
		}
		elseif ( $anchor === "" )
		{
			$regex .= '(())';
		}
		else
		{
			$regex .= '(\#(' . $anchor . '))';
		}

		if ( is_null ( $text ) )
		{
			$regex .= '((\|([^\[\]\|]*\[\[?[^\]]+\]\]?|[^\|\]]+)*)*)';
		}
		elseif ( $text === "" )
		{
			$regex .= '((()))';
		}
		else
		{
			$regex .= '((\|(' . $text . ')))';
		}

		$regex .= '\]\]';

		if ( is_null ( $newline_after ) )
		{
			$regex .= '(\v|$)?';
		}
		elseif ( $newline_after === true )
		{
			$regex .= '(\v|$)';
		}
		else
		{
			$regex .= '()';
		}

		return $regex;
	}

	protected function wikilink_regex ( $colon = NULL, $wiki = NULL,
		$namespace = NULL, $title = NULL, $anchor = NULL, $text = NULL,
		$newline_after = false )
	{
		return '/' . $this->wikilink_regexmatch ( $colon, $wiki,
			$namespace, $title, $anchor, $text, $newline_after ) . '/uS';
	}


	protected function wikilink_parts ( $string )
	{
		if ( preg_match ( $this->wikilink_regex(), $string, $matches ) )
			return array ( 'colon' => ( empty ( $matches[1] ) ? false : true ),
				'wiki' => $matches[3], 'title' => $matches[4],
				'namespace' => $matches[6], 'name' => $matches[7],
				'anchor' => ( empty ( $matches[8] ) ? NULL : $matches[8] ),
				'text' => ( isset ( $matches[12] ) ? $matches[12] : NULL ),
				'newline_after' => ( isset ( $matches[13] ) ? true : false ) );
		else
			return NULL;
	}

	protected function wikilink_string ( $colon = NULL, $wiki = NULL,
		$namespace = NULL, $name = NULL, $anchor = NULL, $text = NULL,
		$newline_after = false )
	{
		if ( is_array ( $colon ) )
			return $this->wikilink_string ( $colon['colon'], $colon['wiki'],
				$colon['namespace'], $colon['name'], $colon['anchor'], $colon['text'],
				$colon['newline_after'] );

		$string = "[[";
		if ( $colon )
			$string .= ":";
		if ( ! empty ( $wiki ) )
			$string .= $wiki . ":";
		if ( ! empty ( $namespace ) )
			$string .= $namespace . ":";
		if ( ! empty ( $name ) )
			$string .= $name;
		if ( ! empty ( $anchor ) )
			$string .= "#" . $anchor;
		if ( ! empty ( $text ) )
			$string .= "|" . $text;
		$string .= "]]";
		if ( $newline_after )
			$string .= "\n";
		return $string;
	}


	# --- Public --- #

	public function wikilinks_strings ( $colon = NULL, $wiki = NULL,
		$namespace = NULL, $name = NULL, $anchor = NULL, $text = NULL )
	{
		$regex = $this->wikilink_regex ( $colon, $wiki, $namespace,
			$name, $anchor, $text );
		$alllinks = ( preg_match_all ( $regex, $this->text, $matches )
			? $matches[0]
			: array() );

		$categories = $this->categories_strings ( $name, $text, $namespace );
		$interwikis = $this->interwikis_strings ( $wiki, $namespace, $name );
		$filelinks  = $this->filelinks_strings  ( $name, $namespace );

		return array_diff ( $alllinks, $categories, $interwikis, $filelinks );
	}

	public function wikilinks ( $colon = NULL, $wiki = NULL,
		$namespace = NULL, $name = NULL, $anchor = NULL, $text = NULL )
	{
		$strings = $this->wikilinks_strings (
			$colon, $wiki, $namespace, $name, $anchor, $text );

		$wikilinks = array();
		foreach ( $strings as $string )
			$wikilinks[] = $this->wikilink_parts ( $string );

		return $wikilinks;
	}

	public function wikilink_exists ( $colon = NULL, $wiki = NULL,
		$namespace = NULL, $name = NULL, $anchor = NULL, $text = NULL )
	{
		$regex = $this->wikilink_regex ( $colon, $wiki, $namespace,
			$name, $anchor, $text );
		return (bool) preg_match ( $regex, $this->text );
	}

	public function replace_wikilink ( $colon = NULL, $wiki = NULL,
		$namespace = NULL, $name = NULL, $anchor = NULL, $text = NULL,
		$with = NULL, $limit = -1 )
	{
		$regex = $this->wikilink_regex ( $colon, $wiki, $namespace,
			$name, $anchor, $text );
		return $this->replace_regex ( $regex, $with, $limit );
	}

	public function unlink_wikilink ( $colon = NULL, $wiki = NULL,
		$namespace = NULL, $name = NULL, $anchor = NULL, $text = NULL,
		$limit = -1 )
	{
		$regex = $this->wikilink_regex ( $colon, $wiki, $namespace,
			$name, $anchor, $text );
		return $this->replace_regex ( $regex, '$12', $limit );
	}

	public function wikilink_text_by_regex ( $regex, $colon = NULL, $wiki = NULL,
		$namespace = NULL, $name = NULL, $anchor = NULL,
		$limit = 1 )
	{
		$with = $this->wikilink_string ( $colon, $wiki, $namespace, $name,
			$anchor, '$0' );
		return $this->replace_regex ( $regex, $with, $limit );
	}

	public function wikilink_text ( $text, $colon = NULL, $wiki = NULL,
		$namespace = NULL, $name = NULL, $anchor = NULL, $limit = 1 )
	{
		$regex = '/' . preg_quote ( $text ) . '/u';
		$with = $this->wikilink_string ( $colon, $wiki, $namespace, $name,
			$anchor, $text );
		return $this->replace_regex ( $regex, $with, $limit );
	}

	public function replace_wikilink_target ( $colon = NULL, $wiki = NULL,
		$namespace = NULL, $name = NULL, $anchor = NULL, $text = NULL,
		$new_target = NULL, $limit = -1, $remove_text_matching_target = true )
	{
		$regex = $this->wikilink_regex ( $colon, $wiki, $namespace,
			$name, $anchor, $text );
		$result = $this->replace_regex ( $regex, '[[$1$2' . $new_target . '$8$10]]',
			$limit );

		if ( $remove_text_matching_target )
		{
			$regex = $this->wikilink_regex ( $colon, $wiki, $namespace,
				preg_quote ( $new_target ), "", preg_quote ( $new_target ) );
			$this->replace_regex ( $regex, '[[$1$2$4]]' );
		}

		return $result;
	}

	public function replace_wikilink_text ( $colon = NULL, $wiki = NULL,
		$namespace = NULL, $name = NULL, $anchor = NULL, $text = NULL,
		$new_text = NULL, $limit = -1, $remove_text_matching_target = true )
	{
		$regex = $this->wikilink_regex ( $colon, $wiki, $namespace,
			$name, $anchor, $text );
		if ( ! empty ( $new_text ) )
			$new_text = '|' . $new_text;
		$result = $this->replace_regex ( $regex, '[[$1$2$4$8' . $new_text . ']]', $limit );

		if ( $remove_text_matching_target )
		{
			$regex = $this->wikilink_regex ( $colon, $wiki, $namespace,
				preg_quote ( $new_text ), "", preg_quote ( $new_text ) );
			$this->replace_regex ( $regex, '[[$1$2$4]]' );
		}

		return $result;
	}

	public function add_wikilink ( $wikilink,
		$regexpart_before = NULL, $regexpart_after = NULL, $limit = -1 )
	{
		if ( is_array ( $wikilink ) )
			return $this->insert ( $this->wikilink_string ( $wikilink ),
				$regexpart_before, $regexpart_after, $limit );

		return $this->insert ( $wikilink,
			$regexpart_before, $regexpart_after, $limit );
	}

	public function delete_wikilinks ( $colon = NULL, $wiki = NULL,
		$namespace = NULL, $name = NULL, $anchor = NULL, $text = NULL, $limit = -1 )
	{
		$regex = $this->wikilink_regex ( $colon, $wiki, $namespace,
			$name, $anchor, $text );
		return $this->delete ( $regex, $limit );
	}


	# ----- Categories ----- #

	# --- Tools --- #

	protected function category_regexmatch ( $name = NULL,
		$sortkey = NULL, $namespace = NULL, $newline_after = NULL )
	{
		if ( is_array ( $name ) ) {
			$sortkey = ( isset ( $name['sortkey'] ) ? $name['sortkey'] : NULL );
			$namespace = ( isset ( $name['namespace'] ) ? $name['namespace'] : NULL );
			$newline_after =
				( isset ( $name['newline_after'] ) ? $name['newline_after'] : NULL );
			$name = ( isset ( $name['name'] ) ? $name['name'] : NULL );
		}

		if ( is_null ( $namespace ) )
			$namespace = $this->core->info->namespace_barsepnames ( 'Category' );

		return $this->wikilink_regexmatch ( false, NULL, $namespace, $name,
			NULL, $sortkey, $newline_after );
	}

	protected function category_regex ( $name = NULL, $sortkey = NULL,
		$namespace = NULL, $newline_after = NULL )
	{
		return '/' . $this->category_regexmatch ( $name, $sortkey, $namespace,
			$newline_after ) . '/uS';
	}


	protected function category_parts ( $string )
	{
		if ( preg_match ( $this->category_regex(), $string, $matches ) )
		{
			return array ( 'title' => $matches[4], 'namespace' => $matches[6],
				'name' => $matches[7],
				'sortkey' => ( isset ( $matches[12] ) ? $matches[12] : NULL ),
				'newline_after' => ( isset ( $matches[13] ) ? $matches[13] : NULL ) );
		}
		else
		{
			return NULL;
		}
	}

	protected function category_string ( $name, $sortkey = NULL,
		$namespace = NULL, $newline_after = NULL )
	{
		if ( is_array ( $name ) ) {
			$sortkey = ( isset ( $name['sortkey'] ) ? $name['sortkey'] : NULL );
			$namespace = ( isset ( $name['namespace'] ) ? $name['namespace'] : NULL );
			$newline_after =
				( isset ( $name['newline_after'] ) ? $name['newline_after'] : NULL );
			$name = ( isset ( $name['name'] ) ? $name['name'] : NULL );
		}

		if ( is_null ( $namespace ) )
			$namespace = $this->core->info->namespace_basic_name ( 'Category' );

		return $this->wikilink_string (
			NULL, NULL, $namespace, $name, NULL, $sortkey, $newline_after );
	}


	# --- Public --- #

	public function categories_strings ( $name = NULL, $sortkey = NULL,
		$namespace = NULL, $newline_after = NULL )
	{
		$regex = $this->category_regex ( $name, $sortkey, $namespace,
			$newline_after );
		return ( preg_match_all ( $regex, $this->text, $matches )
			? $matches[0]
			: array() );
	}

	public function categories ( $name = NULL, $sortkey = NULL,
		$namespace = NULL, $newline_after = NULL )
	{
		$strings = $this->categories_strings ( $name, $sortkey, $namespace,
			$newline_after );
		$categories = array();
		foreach ( $strings as $string )
			$categories[] = $this->category_parts ( $string );
		return $categories;
	}


	public function categories_names ( $name = NULL, $sortkey = NULL,
		$namespace = NULL, $newline_after = NULL )
	{
		$regex = $this->category_regex ( $name, $sortkey, $namespace,
			$newline_after );
		return ( preg_match_all ( $regex, $this->text, $matches )
			? $matches[7]
			: array() );
	}

	public function categories_titles ( $name = NULL, $sortkey = NULL,
		$namespace = NULL, $newline_after = NULL )
	{

		$regex = $this->category_regex ( $name, $sortkey, $namespace,
			$newline_after );
		return ( preg_match_all ( $regex, $this->text, $matches )
			? $matches[4]
			: array() );
	}


	public function category_exists ( $name, $sortkey = NULL, $namespace = NULL )
	{
		if ( is_array ( $name ) )
		{
			$sortkey = ( isset ( $name['sortkey'] ) ? $name['sortkey'] : NULL );
			$namespace = ( isset ( $name['namespace'] ) ? $name['namespace'] : NULL );
			$name = $name['name'];
		}

		$regex = $this->category_regex ( $name, $sortkey, $namespace );
		return (bool) preg_match ( $regex, $this->text );
	}


	public function add_category_string ( $string )
	{
		$category = $this->category_parts ( $string );
		$name = $category['name'];

		if ( $this->category_exists ( $name ) )
			return false;

		if ( preg_match_all ( $this->category_regex(), $this->text, $matches,
			PREG_OFFSET_CAPTURE | PREG_SET_ORDER ) )
		{
			foreach ( $matches as $match )
				if ( strcmp ( $match[7][0], $name ) == 1 )
				{
					$offset = $match[0][1];
					break;
				}

			if ( ! isset ( $offset ) )
			{
				$lastcat = end ( $matches );
				$offset = $lastcat[0][1] + strlen ( $lastcat[0][0] );
			}

			$this->text = substr_replace ( $this->text, $string, $offset, 0 );
			return true;
		}

		if ( preg_match_all ( $this->interwiki_regex(), $this->text, $matches,
			PREG_OFFSET_CAPTURE | PREG_SET_ORDER ) )
		{
			$firstiw = reset ( $matches );
			$offset = $firstiw[0][1];
			$this->text =
				substr_replace ( $this->text, $string . "\n", $offset, 0 );
			return true;
		}

		$this->append ( "\n\n" . $string );
		return true;
	}

	public function add_category ( $name, $sortkey = NULL, $namespace = NULL,
		$newline_after = true )
	{
		$string = $this->category_string ( $name, $sortkey, $namespace,
			$newline_after );
		return $this->add_category_string ( $string );
	}

	public function add_categories ( $categories )
	{
		$counter = 0;
		foreach ( $categories as $category )
			if ( $this->add_category ( $category ) )
				$counter++;
		return $counter;
	}


	public function delete_categories ( $name = NULL )
	{
		return $this->delete ( $this->category_regex ( $name ) );
	}


	public function extract_categories_strings ( $name = NULL )
	{
		$strings = $this->categories_strings ( $name );
		foreach ( $strings as $string )
		{
			$this->delete ( '/' . preg_quote ( $string ) . '/u' );
		}
		return $strings;
	}

	public function extract_categories ( $name = NULL )
	{
		$strings = $this->extract_categories_strings ( $name );
		$categories = array();
		foreach ( $strings as $string )
			$categories[] = $this->category_parts ( $string );
		return $categories;
	}


	public function replace_category ( $old_name, $new_name,
		$new_sortkey = NULL )
	{
		if ( is_null ( $new_sortkey ) )
			$new_sortkey = '$12';

		$regex = $this->category_regex ( $old_name );
		$with  = $this->category_string ( $new_name, $new_sortkey );

		return $this->replace_regex ( $regex, $with );
	}


	public function category_sortkey ( $name )
	{
		$regex = $this->category_regex ( $name );
		return ( preg_match ( $regex, $this->text, $matches )
			? ( isset ( $matches[12] ) ? $matches[12] : "" )
			: NULL );
	}

	public function set_category_sortkey ( $name, $new_sortkey )
	{
		$regex = $this->category_regex ( $name );

		if ( is_array ( $name ) ) {
			$namespace = ( isset ( $name['namespace'] ) ? $name['namespace'] : NULL );
			$name = ( isset ( $name['name'] ) ? $name['name'] : NULL );
		}
		$with = $this->category_string ( $name, $new_sortkey, $namespace );

		return $this->replace_regex ( $regex, $with );
	}

	public function delete_category_sortkey ( $name )
	{
		return $this->set_category_sortkey ( $name, NULL );
	}


	# ----- Interwikis ----- #

	# --- Tools --- #

	# be careful with the namespace - their names differ between wikis!
	protected function interwiki_regexmatch ( $wiki = '*', $namespace = NULL,
		$name = NULL, $newline_after = NULL )
	{
		if ( is_array ( $wiki ) ) {
			$namespace = ( isset ( $wiki['namespace'] ) ? $wiki['namespace'] : NULL );
			$name = ( isset ( $wiki['name'] ) ? $wiki['name'] : NULL );
			$newline_after =
				( isset ( $wiki['newline_after'] ) ? $wiki['newline_after'] : NULL );
			$wiki = ( isset ( $wiki['wiki'] ) ? $wiki['wiki'] : NULL );
		}

		$regex = '/([^\:\#\]]+)\:(.+)$/u';
		if ( empty ( $name ) && preg_match ( $regex, $namespace, $matches ) )  // title
		{
			$namespace = $matches[1];
			$name = $matches[2];
		}

		return $this->wikilink_regexmatch ( false, $wiki, $namespace, $name, "", "",
			$newline_after );
	}

	protected function interwiki_regex ( $wiki = '*', $namespace = NULL,
		$name = NULL, $newline_after = NULL )
	{
		return '/' . $this->interwiki_regexmatch ( $wiki, $namespace, $name,
			$newline_after ) . '/uS';
	}


	protected function interwiki_parts ( $string )
	{
		if ( preg_match ( $this->interwiki_regex(), $string, $matches ) )
		{
			return array ( 'wiki' => $matches[3], 'title' => $matches[4],
				'namespace' => $matches[6], 'name' => $matches[7],
				'newline_after' => ( isset ( $matches[13] ) ? $matches[13] : NULL ) );
		}
		else
		{
			return NULL;
		}
	}

	protected function interwiki_string ( $wiki, $namespace = NULL, $name = NULL,
		$newline_after = NULL )
	{
		if ( is_array ( $wiki ) )
		{
			$namespace = ( isset ( $wiki['namespace'] ) ? $wiki['namespace'] : NULL );
			$name = ( isset ( $wiki['name'] ) ? $wiki['name'] : NULL );
			$newline_after =
				( isset ( $wiki['newline_after'] ) ? $wiki['newline_after'] : NULL );
			$wiki = ( isset ( $wiki['wiki'] ) ? $wiki['wiki'] : NULL );
		}

		if ( empty ( $name ) )
		{
			$regex = '/' . $this->core->info->namespaces_namesregex() . '\:(.+)$/u';
			if ( preg_match ( $regex, $namespace, $matches ) )
			{
				$namespace = $matches[1];
				$name = $matches[2];
			}
			else
			{
				$name = $namespace;
				$namespace = NULL;
			}
		}

		return $this->wikilink_string ( NULL, $wiki, $namespace, $name, NULL, NULL,
			$newline_after );
	}


	# --- Public --- #

	public function interwikis_strings ( $wiki = '*', $namespace = NULL,
		$name = NULL, $newline_after = NULL )
	{
		$regex = $this->interwiki_regex ( $wiki, $namespace, $name, $newline_after );
		return ( preg_match_all ( $regex, $this->text, $matches )
			? $matches[0]
			: array() );
	}

	public function interwikis ( $wiki = '*', $namespace = NULL, $name = NULL,
		$newline_after = NULL )
	{
		$strings = $this->interwikis_strings ( $wiki, $namespace, $name,
			$newline_after );
		$interwikis = array();
		foreach ( $strings as $string )
			$interwikis[] = $this->interwiki_parts ( $string );
		return $interwikis;
	}


	public function interwikis_wikis ( $wiki = '*', $namespace = NULL,
		$name = NULL, $newline_after = NULL )
	{
		$regex = $this->interwiki_regex ( $wiki, $namespace, $name, $newline_after );
		return ( preg_match_all ( $regex, $this->text, $matches )
			? $matches[3]
			: array() );
	}


	public function interwiki_exists ( $wiki, $namespace = NULL, $name = NULL )
	{
		$regex = $this->interwiki_regex ( $wiki, $namespace, $name );
		return (bool) preg_match ( $regex, $this->text );
	}

	public function interwiki_target ( $wiki )
	{
		$regex = $this->interwiki_regex ( $wiki );
		return ( preg_match ( $regex, $this->text, $matches )
			? $matches[4] . ( isset ( $matches[8] ) ? $matches[8] : "" )
			: NULL );
	}


	public function add_interwiki_string ( $string )
	{
		$interwiki = $this->interwiki_parts ( $string );
		if ( $this->interwiki_exists ( $interwiki['wiki'] ) )
			return false;

		if ( preg_match_all ( $this->interwiki_regex(), $this->text, $matches,
			PREG_OFFSET_CAPTURE | PREG_SET_ORDER ) )
		{
			foreach ( $matches as $match )
				if ( strcmp ( $match[3][0], $interwiki['wiki'] ) == 1 )
				{
					$offset = $match[0][1];
					break;
				}
			if ( ! isset ( $offset ) )
			{
				$lastiw = end ( $matches );
				$offset = $lastiw[0][1] + strlen ( $lastiw[0][0] );
			}

			$this->text = substr_replace ( $this->text, $string, $offset, 0 );
			return true;
		}

		$this->append ( "\n\n" . $string );
		return true;
	}

	public function add_interwiki ( $wiki, $namespace = NULL, $name = NULL,
		$newline_after = NULL )
	{
		$string = $this->interwiki_string ( $wiki, $namespace, $name,
			$newline_after );
		$this->delete_interwikis ( $wiki );
		return $this->add_interwiki_string ( $string );
	}

	public function add_interwikis ( $interwikis )
	{
		$strings = $this->extract_interwikis_strings();
		$old_iwcount = count ( $strings );
		foreach ( $interwikis as $interwiki )
			$strings[] = $this->interwiki_string ( $interwiki );
		sort ( $strings );
		$strings = array_unique ( $strings );
		foreach ( $strings as $string )
			$this->append ( $string );
		return count ( $strings ) - $old_iwcount;
	}


	public function delete_interwikis ( $wiki = '*' )
	{
		$regex = $this->interwiki_regex ( $wiki, NULL, NULL, NULL );
		return $this->delete ( $regex );
	}


	public function extract_interwikis_strings ( $wiki = '*' )
	{
		$strings = $this->interwikis_strings ( $wiki );
		foreach ( $strings as $string )
			$this->delete ( '/' . preg_quote ( $string ) . '/u' );
		return $strings;
	}

	public function extract_interwikis ( $wiki = '*' )
	{
		$strings = $this->extract_interwikis_strings ( $wiki );
		$interwikis = array();
		foreach ( $strings as $string )
			$interwikis[] = $this->interwiki_parts ( $string );
		return $interwikis;
	}


	public function replace_interwiki ( $old_wiki, $new_wiki, $new_title )
	{
		if ( is_array ( $old_wiki ) )
			return $this->replace_interwiki ( $old_wiki['wiki'], $new_wiki,
				$new_title );
		if ( is_array ( $new_wiki ) )
			return $this->replace_interwiki ( $old_wiki, $new_wiki['wiki'],
				$new_wiki['title'] );
		if ( is_null ( $new_title ) )
			$new_title = '$4$8';

		$regex = $this->interwiki_regex ( $old_wiki );
		$with  = $this->interwiki_string ( $new_wiki, $new_title );
		return $this->replace_regex ( $regex, $with );
	}


	public function set_interwiki_target ( $wiki, $new_title )
	{
		if ( is_array ( $wiki ) )
			return $this->set_interwiki_target ( $wiki['wiki'], $new_title );

		$regex = $this->interwiki_regex ( $wiki );
		$with  = $this->interwiki_string ( $wiki, $new_title );
		return $this->replace_regex ( $regex, $with );
	}


	# ----- Filelinks ----- #

	# --- Tools --- #

	protected function filelink_regexmatch ( $name = NULL, $namespace = NULL )
	{
		if ( is_array ( $name ) )
		{
			$namespace = ( isset ( $name['namespace'] ) ? $name['namespace'] : NULL );
			$name = ( isset ( $name['name'] ) ? $name['name'] : NULL );
		}

		if ( is_null ( $namespace ) )
			$namespace = $this->core->info->namespace_barsepnames ( 'File' );
		return $this->wikilink_regexmatch ( NULL, NULL, $namespace, $name );
	}

	protected function filelink_regex ( $name = NULL, $namespace = NULL )
	{
		return '/' . $this->filelink_regexmatch ( $name, $namespace ) . '/uS';
	}


	protected function filelink_parts ( $string )
	{
		$parts = $this->page_element_pieces ( $string, '[[', ']]' );
		if ( is_null ( $parts ) )
			return NULL;

		$title = array_shift ( $parts );
		$title_parts = $this->core->info->title_parts ( $title );

		$filelink = array (
			'title' => $title,
			'namespace' => trim ( $title_parts['namespace'] ),
			'name' => trim ( $title_parts['name'] ),
			'params' => array(),
		);

		$param_regexes = array (
			'format' => '/(' .
				$this->core->info->magicword_barsepnames ( 'border', true ) . '|' .
				$this->core->info->magicword_barsepnames ( 'frameless', true ) . '|' .
				$this->core->info->magicword_barsepnames ( 'frame', true ) . '|' .
				$this->core->info->magicword_barsepnames ( 'thumb', true ) . '|' .
				$this->core->info->magicword_barsepnames ( 'thumbnail', true ) . ')/uS',

			'resize' => '/(' .
				'\d+(' . preg_replace ( '/\$\d+/uS', '',
					$this->core->info->magicword_barsepnames ( 'img_width', true ) ) .
					')|' .
				'x\d+(' . preg_replace ( '/\$\d+/uS', '',
					$this->core->info->magicword_barsepnames ( 'img_width', true ) ) .
					')|' .
				'\d+x\d+(' . preg_replace ( '/\$\d+/uS', '',
					$this->core->info->magicword_barsepnames ( 'img_width', true ) ) .
					')|' .
				'upright)/uS',

			'align' => '/(' .
				$this->core->info->magicword_barsepnames ( 'left', true ) . '|' .
				$this->core->info->magicword_barsepnames ( 'right', true ) . '|' .
				$this->core->info->magicword_barsepnames ( 'center', true ) . '|' .
				$this->core->info->magicword_barsepnames ( 'none', true ) . ')/uS',

			'valign' => '/(' .
				$this->core->info->magicword_barsepnames ( 'baseline', true ) . '|' .
				$this->core->info->magicword_barsepnames ( 'sub', true ) . '|' .
				$this->core->info->magicword_barsepnames ( 'super', true ) . '|' .
				$this->core->info->magicword_barsepnames ( 'top', true ) . '|' .
				$this->core->info->magicword_barsepnames ( 'text-top', true ) . '|' .
				$this->core->info->magicword_barsepnames ( 'middle', true ) . '|' .
				$this->core->info->magicword_barsepnames ( 'bottom', true ) . '|' .
				$this->core->info->magicword_barsepnames ( 'text-bottom', true ) .
				')/uS',

			'link' => '/^(' . preg_replace ( '/\$\d+/uS', '',
				$this->core->info->magicword_barsepnames ( 'img_link', true ) ) .
				')/uS',

			'alt' => '/^(' . preg_replace ( '/\$\d+/uS', '',
				$this->core->info->magicword_barsepnames ( 'img_alt', true ) ) .
				')/uS',

			'page' => '/^(' . preg_replace ( '/\$\d+/uS', '',
				$this->core->info->magicword_barsepnames ( 'img_page', true ) ) .
				')/uS',
		);

		foreach ( $parts as $part )
		{
			$part = trim ( $part );
			if ( empty ( $part ) )
				continue;

			foreach ( $param_regexes as $key => $regex )
			{
				if ( preg_match ( $regex, $part ) )
				{
					$filelink['params'][$key] = $part;
					unset ( $part );
					break;
				}

			}

			if ( isset ( $part ) )
				if ( empty ( $filelink['caption'] ) )
				{
					$filelink['caption'] = $part;
				}
				else
				{
					return NULL;
				}

		}

		return $filelink;
	}

	protected function filelink_string ( $name, $params = NULL, $caption = NULL,
		$namespace = NULL )
	{
		if ( is_array ( $name ) )
			return $this->filelink_string ( $name['name'],
				( isset ( $name['params'] ) ? $name['params'] : NULL ),
				( isset ( $name['caption'] ) ? $name['caption'] : NULL ),
				( isset ( $name['namespace'] ) ? $name['namespace'] : NULL ) );
		if ( empty ( $namespace ) )
			$namespace = $this->core->info->namespace_basic_name ( 'File' );
		if ( is_null ( $params ) )
			$params = array();
		return $this->wikilink_string ( NULL, NULL, $namespace, $name, NULL,
			implode ( '|', $params ) . ( empty ( $caption ) ? "" : '|' . $caption ) );
	}


	# --- Public --- #

	public function filelinks_strings ( $name = NULL, $namespace = NULL )
	{
		$regex = $this->filelink_regex ( $name, $namespace );
		return ( preg_match_all ( $regex, $this->text, $matches )
			? $matches[0]
			: array() );
	}

	public function filelinks ( $name = NULL, $namespace = NULL )
	{
		$strings = $this->filelinks_strings ( $name, $namespace );
		$filelinks = array();
		foreach ( $strings as $string )
			$filelinks[] = $this->filelink_parts ( $string );
		return $filelinks;
	}

	public function filelink_exists ( $name, $namespace = NULL )
	{
		$regex = $this->filelink_regex ( $name, $namespace );
		return (bool) preg_match ( $regex, $this->text );
	}

	public function replace_filelink ( $name, $namespace = NULL, $with = NULL,
		$limit = -1 )
	{
		if ( is_array ( $with ) )
			$with = $this->filelink_string ( $with );
		$regex = $this->filelink_regex ( $name, $namespace );
		return $this->replace_regex ( $regex, $with, $limit );
	}

	public function replace_filelink_name ( $old_name, $new_name,
		$old_namespace = NULL, $new_namespace = NULL, $limit = -1 )
	{
		$regex = $this->filelink_regex ( $old_name, $old_namespace );
		if ( is_null ( $new_namespace ) )
			$new_namespace = '$5';
		else
			$new_namespace .= ':';
		$with  = '[[$1$2' . $new_namespace . $new_name . '$10]]';
		return $this->replace_regex ( $regex, $with, $limit );
	}

	public function replace_filelink_param ( $name, $param, $new_value,
		$namespace = NULL )
	{
		$strings = $this->filelinks_strings ( $name, $namespace );
		$counter = 0;
		foreach ( $strings as $string )
		{
			$filelink = $this->filelink_parts ( $string );
			if ( is_null ( $new_value ) )
				unset ( $filelink['params'][$param] );
			else
				$filelink['params'][$param] = $new_value;
			$new_string = $this->filelink_string ( $filelink );

			$counter += $this->replace_string ( $string, $new_string );
		}
		return $counter;
	}

	public function replace_filelink_caption ( $name, $new_caption,
		$namespace = NULL )
	{
		$strings = $this->filelinks_strings ( $name, $namespace );
		$counter = 0;
		foreach ( $strings as $string )
		{
			$filelink = $this->filelink_parts ( $string );
			$filelink['caption'] = $new_caption;
			$new_string = $this->filelink_string ( $filelink );

			$counter += $this->replace_string ( $string, $new_string );
		}
		return $counter;
	}

	public function add_filelink ( $filelink,
		$regexpart_before = NULL, $regexpart_after = NULL, $limit = -1 )
	{
		if ( is_array ( $filelink ) )
			$filelink = $this->filelink_string ( $filelink );

		return $this->insert ( $filelink, $regexpart_before, $regexpart_after,
			$limit );
	}

	public function delete_filelinks ( $name, $namespace = NULL, $limit = -1 )
	{
		$regex = $this->filelink_regex ( $name, $namespace );
		return $this->delete ( $regex, $limit );
	}


	# ----- Templates ----- #

	# --- Tools --- #

	protected function template_regexmatch ( $name = NULL, $namespace = NULL,
		$wiki = NULL )
	{

		if ( is_array ( $name ) )
		{
			$wiki = ( isset ( $name['wiki'] ) ? $name['wiki'] : NULL );
			$namespace = ( isset ( $name['namespace'] ) ? $name['namespace'] : NULL );
			$name = ( isset ( $name['name'] ) ? $name['name'] : NULL );
		}

		if ( empty ( $name ) )
			$name = '[^\}\|]+';
		if ( ! is_null ( $namespace ) )
			$name = $namespace . '\h*\:\h*' . $name;
		if ( ! is_null ( $wiki ) )
			$name = $wiki . '\h*\:\h*' . $name;

		return '\{\{\s*(' . $name . ')\s*\|?' .
			'([^\{\}]*\{\{' .
				'([^\{\}]*\{\{' .
					'([^\{\}]*\{\{' .
						'([^\{\}]*\{\{' .
							'[^\{\}]*' .  // up to 5 inlayed templates are currently allowed
						'\}\}[^\{\}]*' .
						'|[^\{\}]*)*' .
					'\}\}[^\{\}]*' .
					'|[^\{\}]*)*' .
				'\}\}[^\{\}]*' .
				'|[^\{\}]*)*' .
			'\}\}[^\{\}]*' .
			'|[^\{\}]*)*' .
		'\}\}';
	}

	protected function template_regex ( $name = NULL, $namespace = NULL,
		$wiki = NULL )
	{
		return '/' . $this->template_regexmatch ( $name ) . '/u';
	}


	protected function template_parts ( $string )
	{
		$parts = $this->page_element_pieces ( $string, '{{', '}}' );
		if ( is_null ( $parts ) )
			return NULL;

		$template = array (
			'title' => trim ( array_shift ( $parts ) ),
			'params' => array(),
			'multiline' => preg_match ( '/\v/uS', $string ),
		);

		$title_parts = $this->core->info->title_parts ( $template['title'] );
		if ( isset ( $title_parts['wiki'] ) )
			$template['wiki'] = $title_parts['wiki'];
		if ( isset ( $title_parts['namespace'] ) )
			$template['namespace'] = $title_parts['namespace'];
		if ( isset ( $title_parts['name'] ) )
			$template['name'] = $title_parts['name'];

		foreach ( $parts as $part )
		{
			$pair = preg_split ( '/\=/uS', $part, 2 );
			if ( count ( $pair ) == 1 )
				$template['params'][] = trim ( $pair[0] );
			else
				$template['params'][ trim ( $pair[0] ) ] = trim ( $pair[1] );
		}

		return $template;
	}

	protected function template_string ( $name, $params = NULL,
		$multiline = NULL )
	{
		if ( is_array ( $name ) )
		{
			if ( empty ( $name['name'] ) )
				$name['name'] = $this->core->info->parts_title ( $name );
			return $this->template_string (
				$name['name'], $name['params'], $name['multiline'] );
		}

		if ( is_null ( $multiline ) )
		{
			if ( empty ( $params ) )
			{
				$multiline = false;
			}
			else
			{
				$multiline = true;
				foreach ( $params as $key => $value )
					if ( is_numeric ( $key ) )
						$multiline = false;
			}
		}

		$string = '{{' . $name;
		foreach ( $params as $key => $value )
			if ( $multiline )
				$string .= "\n| " .
					( is_numeric ( $key ) ? $value : $key . " = " . $value );
			else
				$string .= "|" .
					( is_numeric ( $key ) ? $value : $key . "=" . $value );
		$string .= ( $multiline ? "\n" : "" ) . '}}';

		return $string;
	}


	# --- Public --- #

	public function templates_strings ( $name = NULL, $namespace = NULL,
		$wiki = NULL )
	{
		$regex = $this->template_regex ( $name, $namespace, $wiki );
		return ( preg_match_all ( $regex, $this->text, $matches )
			? $matches[0]
			: array() );
	}

	public function templates ( $name = NULL, $namespace = NULL, $wiki = NULL )
	{
		$strings = $this->templates_strings ( $name, $namespace, $wiki );
		$templates = array();
		foreach ( $strings as $string )
			$templates[] = $this->template_parts ( $string );
		return $templates;
	}

	public function template_exists ( $name, $namespace = NULL, $wiki = NULL )
	{
		$regex = $this->template_regex ( $name, $namespace, $wiki );
		return (bool) preg_match ( $regex, $this->text );
	}

	public function replace_template ( $name, $namespace = NULL, $wiki = NULL,
		$new_template = NULL, $limit = -1 )
	{
		if ( is_array ( $new_template ) )
			$new_template = $this->template_parts ( $new_template );

		$regex = $this->template_regex ( $name, $namespace, $wiki );
		return $this->replace_regex ( $regex, $new_template, $limit );
	}

	public function replace_template_name ( $old_name, $new_name,
		$old_namespace = NULL, $new_namespace = NULL,
		$old_wiki = NULL, $new_wiki = NULL,
		$limit = -1 )
	{
		$old_title = $this->core->info->parts_title (
			array ( 'name' => $old_name, 'namespace' => $old_namespace,
			'wiki' => $old_wiki ) );
		$new_title = $this->core->info->parts_title (
			array ( 'name' => $new_name, 'namespace' => $new_namespace,
			'wiki' => $new_wiki ) );

		return $this->replace ( '{{/s*' . $old_title . '/u', $new_title, $limit );
	}

	public function replace_template_paramname ( $name, $paramname, $new_name,
		$namespace = NULL, $wiki = NULL )
	{
		$strings = $this->templates_strings ( $name, $namespace, $wiki );
		$counter = 0;
		foreach ( $strings as $string )
		{
			$template = $this->template_parts ( $string );
			if ( isset ( $template['params'][$paramname] ) )
			{
				$offset = 0;
				reset ( $template['params'] );
				while ( $offset < count ( $template['params'] ) )
				{
					$pair = each ( $template['params'] );
					if ( $pair['key'] == $paramname )
						break;
					$offset++;
				}
				$template['params'] = array_merge (
					array_slice ( $template['params'], 0, $offset, true ),
					array ( $new_name => $template['params'][$paramname] ),
					array_slice ( $template['params'], $offset + 1, NULL, true )
				);
			}
			$new_string = $this->template_string ( $template );

			$counter += $this->replace_string ( $string, $new_string );
		}
		return $counter;
	}

	public function replace_template_paramvalue ( $name, $paramname, $new_value,
		$namespace = NULL, $wiki = NULL )
	{
		$strings = $this->templates_strings ( $name, $namespace, $wiki );
		$counter = 0;
		foreach ( $strings as $string )
		{
			$template = $this->template_parts ( $string );
			$template['params'][$paramname] = $new_value;
			$new_string = $this->template_string ( $template );

			$counter += $this->replace_string ( $string, $new_string );
		}
		return $counter;
	}

	public function add_template ( $template,
		$regexpart_before = NULL, $regexpart_after = NULL, $limit = -1 )
	{
		if ( is_array ( $template ) )
			$template = $this->template_string ( $template );

		return $this->insert ( $template, $regexpart_before, $regexpart_after,
			$limit );
	}

	public function delete_templates ( $name, $namespace = NULL, $wiki = NULL,
		$limit = -1 )
	{
		$regex = $this->template_regex ( $name, $namespace, $wiki );
		return $this->delete ( $regex, $limit );
	}


	# ---------- Sections ---------- #

	# ----- Protected ----- #

	protected function to_sections ()
	{
		$this->text_sections = $this->text_to_sections();
		unset ( $this->text );
	}

	protected function from_sections ()
	{
		$this->text = $this->sections_to_text();
		unset ( $this->text_sections );
	}


	# ----- Public ----- #

	public function text_to_sections ( $text = NULL )
	{
		if ( is_null ( $text ) )
			$text = $this->text;

		$regex = '/(^|\v)(=+)([^\=\v]+)(=+)(?=\v|$)/us';
		$flags = PREG_SPLIT_DELIM_CAPTURE;
		$pieces = preg_split ( $regex, $text, -1, $flags );

		$sections = array();
		$counter = 0;

		if ( ! preg_match ( '/^\=/u', $pieces[1] ) )
		{
			$counter = 4;
			$section = array();
		}
		foreach ( $pieces as $piece )
		{
			switch ( $counter )
			{
				case 1 :
					$section['level'] = strlen ( $piece );
					break;
				case 2 :
					$section['header'] = trim ( $piece );
					break;
				case 3 :
					$section['level'] = min ( strlen ( $piece ), $section['level'] );
					break;
				case 4 :
					if ( preg_match ( '/^=+/u', $piece ) )
					{
						$sections[] = $section;
						$counter = 1;
						$section = array ( 'level' => strlen ( $piece ) );
					}
					else
					{
						$section['text'] = trim ( $piece );
						$sections[] = $section;
						$counter = -1;
						$section = array();
					}
			}
			$counter++;
		}
		return $sections;
	}

	public function sections_to_text ( $sections )
	{
		if ( is_null ( $sections ) )
			$sections = $this->text_sections;

		if ( ! is_array ( $sections ) )
			return NULL;

		$text = "";
		foreach ( $sections as $section )
		{
			if ( ! empty ( $section['header'] ) )
				$text .= str_repeat ( '=', $section['level'] ) .
					' ' . $section['header'] . ' ' .
					str_repeat ( '=', $section['level'] ) . "\n\n";
			if ( isset ( $section['text'] ) && ! empty ( $section['text'] ) )
				$text .= $section['text'] . "\n\n";
		}
		return $text;
	}


	public function section_no_by_header ( $header )
	{
		if ( ! isset ( $this->text_sections ) )
			return NULL;

		if ( ( $header == "" ) && ! isset ( $this->text_sections[0]['header'] ) )
			return 0;

		$no = 0;
		while ( ++$no < count ( $this->text_sections ) )
			if ( $this->text_sections[$no]['header'] == $header )
				return $no;

		return false;
	}

	public function section_by_header ( $header )
	{
		$no = $this->section_no_by_header ( $header );
		if ( ( $no === false ) || ( $no === NULL ) )
			return false;
		return $this->text_sections[$no];
	}

	public function section_text_by_header ( $header )
	{
		$section = $this->section_by_header ( $header );
		if ( ! is_array ( $section ) )
			return $section;
		else
			return $section['text'];
	}


	public function section_with_subs ( $no )
	{
		if ( ! isset ( $this->text_sections ) )
			return NULL;

		if ( ! is_numeric ( $no ) )
			return false;

		if ( count ( $this->text_sections ) < $no )
			return false;

		$section = $this->text_sections[$no];
		$main_level = $section['level'];

		$sections = array ( $no => $section );
		while ( ++$no < count ( $this->text_sections ) )
			if ( $this->text_sections[$no]['level'] > $main_level )
				$sections[$no] = $this->text_sections[$no];
			else
				break;

		return $sections;
	}

	public function section_with_subs_by_header ( $header )
	{
		return $this->section_with_subs (
			$this->section_no_by_header ( $header ) );
	}


	public function modify_section_level ( $no, $by, $with_subs = true )
	{
		$old_level = $this->text_sections[$no]['level'];
		if ( $this->text_sections[$no]['level'] + $by > 0 )
		{
			$this->text_sections[$no]['level'] += $by;
			if ( $with_subs ) {
				while ( ++$no < count ( $this->text_sections ) )
					if ( $this->text_sections[$no]['level'] > $old_level )
						$this->text_sections[$no]['level'] += $by;
					else
						break;
			}
			return true;
		}
		return false;
	}

	public function modify_section_level_by_header ( $header, $by,
		$with_subs = true )
	{
		$no = $this->section_no_by_header ( $header );
		if ( $no === false )
			return false;
		if ( $no === NULL )
			return NULL;

		return $this->modify_section_level ( $no, $by, $with_subs );
	}


	public function set_section_level ( $no, $level, $with_subs = true )
	{
		return $this->modify_section_level ( $no,
			$level - $this->text_sections[$no]['level'], $with_subs );
	}

	public function set_section_level_by_header ( $header, $level,
		$with_subs = true )
	{
		$no = $this->section_no_by_header ( $header );

		if ( is_numeric ( $no ) )
			return $this->set_section_level ( $no, $level, $with_subs );
		else
			return $no;
	}


	public function insert_sections ( $sections, $no )
	{
		$level = ( ( isset ( $this->text_sections[$no] ) &&
			isset ( $this->text_sections[$no]['level'] ) )
			? $this->text_sections[$no]['level']
			: PHP_INT_MAX );
		while ( ++$no < count ( $this->text_sections ) )
			if ( $this->text_sections[$no]['level'] <= $level )
				break;

		array_splice ( $this->text_sections, $no, 0, $sections );
	}

	public function insert_section ( $section, $no )
	{
		return $this->insert_sections ( array ( $section ), $no );
	}

	public function insert_sections_after_section_with_header ( $sections, $header )
	{
		if ( is_null ( $header ) )
			$no = -1;
		elseif ( $header == "" )
			$no = 0;
		else
			$no = $this->section_no_by_header ( $header );
		if ( $no === false )
			return false;
		if ( $no === NULL )
			return NULL;

		return $this->insert_sections ( $sections, $no );
	}

	public function insert_section_after_section_with_header ( $section, $header )
	{
		return $this->insert_sections_after_section_with_header (
			array ( $section ), $header );
	}


	public function delete_section ( $no, $with_subs = true )
	{
		$count = count ( $this->section_with_subs ( $no ) );
		array_splice ( $this->text_sections, $no, $count );
	}

	public function delete_section_by_header ( $header, $with_subs = true )
	{
		$no = $this->section_no_by_header ( $header );
		if ( $no === false )
			return false;
		if ( $no === NULL )
			return NULL;

		return $this->delete_section ( $no, $with_subs );
	}


	public function move_section ( $current_no, $after_no, $with_subs = true )
	{
		if ( $with_subs )
			$sections = $this->section_with_subs ( $current_no );
		else
			$sections = array ( $this->text_sections[$current_no] );

		if ( $after_no > $current_no )
		{
			$after_no -= count ( $sections );
			if ( $after_no < $current_no )
				return false;
		}
		$this->delete_section ( $current_no, $with_subs );
		$this->insert_sections ( $sections, $after_no );
	}

	public function move_section_by_header ( $header, $after_no,
		$with_subs = true )
	{
		$current_no = $this->section_no_by_header ( $header );
		if ( $current_no === false )
			return false;
		if ( $current_no === NULL )
			return NULL;

		return $this->move_section ( $current_no, $after_no, $with_subs );
	}

	public function move_section_after_section_with_header ( $header,
		$after_header, $with_subs = true )
	{
		$after_no = $this->section_no_by_header ( $after_header );
		if ( $after_no === false )
			return false;
		if ( $after_no === NULL )
			return NULL;

		return $this->move_section_by_header ( $header, $after_no, $with_subs );
	}


	# ---------- Lists ---------- #

	private function list_string ( $list_array, $ordered_list = false, $level = 1 )
	{
		$list_preface = ( $ordered_list ? "#" : "*" );

		$result = "";
		foreach ( $list_array as $member )
			if ( is_array ( $member ) )
				$result .=
					$this->list_string ( $list_array, $ordered_list, $level++ ) . "\n";
			else
				if ( $member === "" )
					$result .= "\n";
				else
					$result .= str_repeat ( $list_preface, $level ) . " " . $member . "\n";

		return $result;
	}

	public function add_list ( $list_array, $ordered_list = false,
		$regexpart_before = NULL, $regexpart_after = NULL )
	{
		if ( is_array ( $list_array ) )
			$list_array = $this->list_string ( $list_array, $ordered_list,
				$regexpart_before, $regexpart_after );

		if ( ! is_string ( $list_array ) )
			return NULL;

		return $this->insert ( $list_array, $regexpart_before, $regexpart_after );
	}


	# ---------- Other tools ---------- #

	public function neat ()
	{
		$categories = $this->extract_categories_strings();
		$interwikis = $this->extract_interwikis_strings();

		$this->to_sections();
		$this->from_sections();

		$this->append ( "\n" );
		foreach ( $categories as $category )
			$this->add_category ( $category );

		$this->append ( "\n" );
		foreach ( $interwikis as $interwiki )
			$this->add_interwiki ( $interwiki );
	}


}
