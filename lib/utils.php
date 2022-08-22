<?php
/**
 * Utility functions
 */
function add_filters($tags, $function) {
  foreach($tags as $tag) {
    add_filter($tag, $function);
  }
}

function is_element_empty($element) {
  $element = trim($element);
  return empty($element) ? false : true;
}

add_filter( 'no_texturize_tags', 'sdss_no_texturize_tags' );
function sdss_no_texturize_tags( $tags = array() ) {
	
	if ( !in_array( 'code' , $tags ) ) {$tags[] = 'code';}
	if ( !in_array( 'pre' , $tags ) ) {$tags[] = 'pre';}
	
	return $tags;
	
}

add_filter( 'no_texturize_shortcodes', 'sdss_no_texturize_shortcodes' );
function sdss_no_texturize_shortcodes( $shortcodes ) {
    $shortcodes[] = 'SDSS_TOC';
    $shortcodes[] = 'SDSS_GROUP';
    $shortcodes[] = 'SDSS_STORY';
    $shortcodes[] = 'SDSS_FIGURE';
    $shortcodes[] = 'sdss_boilerplate';
    $shortcodes[] = 'SDSS_GALLERY';
    $shortcodes[] = 'SDSS_TOTOP';
    $shortcodes[] = 'SDSS_PUBLICATION';
    return $shortcodes;
}