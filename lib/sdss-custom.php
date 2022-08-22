<?php
/**
 * Custom functions for SDSS
 */

/**
 * FILTERS
 */

add_filter('roots_wrap_base', 'roots_wrap_base_cpts'); // Add our function to the roots_wrap_base filter
function roots_wrap_base_cpts($templates) {
	$cpt = get_post_type(); // Get the current post type
	if ($cpt) {
		array_unshift($templates, 'base-' . $cpt . '.php'); // Shift the template to the front of the array
	}
	return $templates; // Return our modified array with base-$cpt.php at the front of the queue
}

// Don't let users use Visual content editing - it screws up special characters.
add_filter( 'user_can_richedit' , '__return_false', 50 );

// Add and edit special fields for SDSS Media attachments

add_filter( 'attachment_fields_to_edit', 'sdss_edit_attachment_gallery', 10, 2 );
function sdss_edit_attachment_gallery( $form_fields, $post ) {
	
	//Show In SDSS Gallery Checkbox
    $value = (bool) get_post_meta( $post->ID, '_gallery', true );
    $checked = ($value) ? 'checked' : '';
    $form_fields['gallery'] = array(
		'label' => __( 'Show in SDSS Gallery.' ),
        'input' => 'html',
		'html' => "<input type='checkbox' " . 
					" $checked " . 
					"name='attachments[{$post->ID}][gallery]' " .
					"id='attachments-".$post->ID."-gallery' " .
					">",
		'extra_rows' => array("row1" => "<em>Applies to www only. All images are show on testng and test<em><br>&nbsp;") ,
        'value' => $value,
	);
	
	// License / Image Use textarea
    $form_fields['license'] = array(
		'label' => __( 'License' ),
        'input' => 'html',
		'html' => "<textarea class='widefat'" . 
					"name='attachments[{$post->ID}][license]' " .
					"id='attachments-".$post->ID."-license' " .
					">" . get_post_meta($post->ID, "_license", true ) .
					"</textarea>",
		'extra_rows' => array("row1" => "<em>Leave blank for SDSS General Image Use Policy.<em><br>&nbsp;") ,
	);
	
    $form_fields['credit'] = array(
		'label' => __( 'Image Credit' ),
        'input' => 'html',
		'html' => "<textarea class='widefat'" . 
					"name='attachments[{$post->ID}][credit]' " .
					"id='attachments-".$post->ID."-credit' " .
					">" . get_post_meta($post->ID, "_credit", true ) .
					"</textarea>",
		'extra_rows' => array("row1" => "<em>Leave blank to assign credit to the Sloan Digital Sky Survey.<em><br>&nbsp;") ,
	);
	
	
    return $form_fields;
}

add_action( 'add_attachment', 'sdss_set_attachment_defaults' );
function sdss_set_attachment_defaults( $attachment_id ) {
	add_post_meta( $attachment_id, '_gallery', '0' );
	add_post_meta( $attachment_id, '_credit', '' );
	add_post_meta( $attachment_id, '_license', '' ) ;
}

add_action( 'edit_attachment', 'sdss_edit_attachment' );
function sdss_edit_attachment( $attachment_id ) {
    
	$gallery = ( isset( $_REQUEST['attachments'][$attachment_id]['gallery'] ) &&
				( $_REQUEST['attachments'][$attachment_id]['gallery'] == 'on' ) ) 
				? '1' : '0';
	update_post_meta( $attachment_id, '_gallery', $gallery );
    if ( isset( $_REQUEST['attachments'][$attachment_id]['license'] ) ) {
        $license = $_REQUEST['attachments'][$attachment_id]['license'] ;
        update_post_meta( $attachment_id, '_license', $license );
    }
    if ( isset( $_REQUEST['attachments'][$attachment_id]['credit'] ) ) {
        $credit = $_REQUEST['attachments'][$attachment_id]['credit'] ;
        update_post_meta( $attachment_id, '_credit', $credit );
    }
}

/*
 * Insert a comment on a page. 
 * Doesn't work yet... --Bonnie
 */
add_filter( 'the_content', 'idies_add_comment' );
function idies_add_comment( $content  ) {
	
	global $idies_debug_comment;

	if (!( WP_DEBUG ) || empty( $idies_debug_comment ) ) return $content;
	
	
	foreach( $idies_debug_comment as $this_comment ) {
	
		if ( empty( $this_comment[ 'page' ] ) ) {
		
			$content .= $content . $idies_debug_comment ;
			
		} elseif ( $GLOBALS[ 'post' ]->post_name == $this_comment[ 'page' ] ) {
			$content .= $content . $this_comment[ 'comment' ] ;
		}
	}
	return $content;
}

add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes ( $existing_mimes=array() ) {
	 	 
	// add mime types
	$existing_mimes['fits'] = 'application/fits'; 

	// remove mime types
	//unset( $existing_mimes['exe'] );
	
	// and return the new full result
	return $existing_mimes;
	 
}

// add `vac_id` to query vars
add_filter( 'init', 'sdss_add_vac_query_var' );
function sdss_add_vac_query_var()
{
    global $wp;
    $wp->add_query_var( 'vac_id' );
}

/**
 * ACTIONS
 */
//function sdss_add_categories_to_attachments() {
//    register_taxonomy_for_object_type( 'category', 'attachment' );
//}
//add_action( 'init' , 'sdss_add_categories_to_attachments' );

// apply tags to attachments
function sdss_add_tags_to_attachments() {
    register_taxonomy_for_object_type( 'post_tag', 'attachment' );
}
add_action( 'init' , 'sdss_add_tags_to_attachments' );

// Add to posts query, e.g. for VAC Archive
function idies_get_posts( $query ) {
	// Home page
	if ( is_admin() ) return;
	
	// Get All VAC CPTs and show on one page, initially order by Number
	if ( is_post_type_archive( 'vac' ) ) {
					
		$query->set( 'posts_per_page', -1 );
		$query->set( 'orderby', 'post_title' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'idies_get_posts' );

/*/
// ADD REWRITE RULE FOR VACS
function sdss_rewrite_rule() {
  add_rewrite_rule('^dr14/data_access/value-added-catalogs/vac_id/(.+)/?', 'index.php?page_id=1817&vac_id=$matches[1]', 'top');
}
add_action('init', 'sdss_rewrite_rule');
function sdss_rewrite_tag() {
        add_rewrite_tag('%title%', '([^&]+)');
    }
add_action('init', 'sdss_rewrite_tag', 10, 0);

// Use this to get the vac value
//$wp_query->query_vars['vac']
/*/

/**
 * USEFUL FUNCTIONS
 */

/*
 * Create a comment to insert on a page. 
 * Doesn't work yet... --Bonnie
 */
function idies_comment( $var , $pagename="" ) {
	
	global $idies_debug_comment;
	
	$this_comment = "<!-- DEBUG \n";

	if (is_object($var)) :
		$this_comment .= var_export( $var , true );
	else :
		$this_comment .= print_r( $var , true );
	endif;

	$this_comment .= "\n -->\n";
	$idies_debug_comment[] = array( 'page'=>$pagename , 'comment'=>$this_comment );

	return;
}

/*
 * Show a list of publications
 */
function get_pub_option_list( $ids ) {
	
	$result = "";
	$all=false;
	if ( empty( $ids ) ) return $result;

	$publications_data = get_option( 'sdss_publications' );
	
	if ( -1 == $ids ) {
		$all=true;
	} elseif ( is_string( $ids ) ) {
		$ids = explode( ',' , $ids ); 
		foreach( $ids as $this_key=>$this_id ) $ids[ $this_key ] = intval( $this_id );
	} 
	
	if ( $all ) {
		$show_pubs = $publications_data;
	} else {
		foreach( $ids as $this_id ){
			if ( array_key_exists( $this_id , $publications_data ) ) $show_pubs[ $this_id ] = $publications_data[ $this_id ];
		}
	}
	
	if ( 1 === count( $show_pubs ) ) {
		$result .= get_single_pub_option( $show_pubs[0] );
	} else {
		$result .= "<ul class='fa-ul'>";
		foreach ( $show_pubs as $this_pub ) {
			$result .= "<li><i class='fa-li fa fa-book'></i>";
			$result .= get_single_pub_option( $this_pub );
			$result .= "</li>";
		}
		$result .= "</ul>";
	}
	return $result ;
}

/*
 * Show a single publication given option data.
 */
function get_single_pub_option( $this_pub ) {
	
	$result = '';
	
	$result .= '<div  id="' . sanitize_title( $this_pub['identifier'] ) . '"></div>';
	
	$this_title = ( defined( 'WP_ENV' ) && ( WP_ENV === 'development') ) ?
		"<strong>" . $this_pub['title'] . " (ID: " . $this_pub['identifier'] . ")" . "</strong>" :
		"<strong>" . $this_pub['title'] . "</strong>" ;
	
	// default url to use for publication title
	$dflt_url = ( ( !empty( $this_pub['adsabs_url'] ) ) ? $this_pub['adsabs_url'] : 
				( ( !empty( $this_pub['doi_url'] ) ) ? $this_pub['doi_url'] : 
				( ( !empty( $this_pub['arxiv_url'] ) ) ? $this_pub['arxiv_url'] : 
				false ) ) ) ;
	$result .= ( $dflt_url ) ? "<a target='_blank' href='$dflt_url' >" . $this_title . "</a>" : $this_title ;
	
	$result .=  '<br />' . $this_pub['authors'] .  '. ' ;
	
	$result .= ( empty( $this_pub['journal_reference'] ) ) ? '<em>' . $this_pub['status'] . '</em>' : $this_pub['journal_reference'] ;
	
	$result .= ( empty($this_pub['adsabs'] ) ) ? '' : "; <a href='" . $this_pub['adsabs_url'] . "' target='_blank'>adsabs:" . $this_pub['adsabs'] . "</a>" ;
	
	$result .= ( empty($this_pub['doi'] ) ) ? '' : "; <a href='" . $this_pub['doi_url'] . "' target='_blank'>doi:" . $this_pub['doi'] . "</a>" ;
	
	$result .= ( empty($this_pub['arxiv_url'] ) ) ? '' : "; <a href='" . $this_pub['arxiv_url'] . "' target='_blank'>arXiv:" . $this_pub['arxiv'] . "</a>" ;
	
	return $result ;
}

/*
 * Show a list of publications
 */
function get_pub_list( $pub_titles = array() ) {
	
	//can pass in titles in comma separated value string
	if ( is_string( $pub_titles ) ) $pub_titles = explode( ',' , $pub_titles );
	$result = "";
	
	$pub_args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'title',
		'order'            => 'DESC',
		'post_type'        => 'publication',
		'post_status'      => 'publish',
	);
	if ( empty( $pub_titles ) ) {
		return false;
	} elseif ( $pub_titles == -1 ) {
		$pubs_posts = get_posts( $pub_args );	
	} else {
		$pubs_posts = array();
		foreach ( $pub_titles as $this_title ) {
			$pubs_posts[] = get_page_by_title( $this_title , OBJECT , 'publication' );
		}
	}
	if ( empty( $pubs_posts ) ) return false;
	
	global $post;
	foreach( $pubs_posts as $post ) {
			setup_postdata( $post );
			$single_pub = get_pub_item( $post );
			$result .= ( empty( $single_pub ) ) ? 
				'' : 
				"<li>" .
				"<i class='fa-li fa fa-book'></i>" . $single_pub . 
				"</li>";
	}
	wp_reset_postdata();
	
	$result = "<ul class='fa-ul'>" . $result . "</ul>";
	return $result ;
}

/*
 * Show a single publication given post data.
 */
function get_pub_item( $this_pub = false ) {
	if ( empty( $this_pub ) ) return '';
	
	$result = '<div  id="' . sanitize_title( $this_pub->post_title ) . '"></div>';
	$this_title = ( defined( 'WP_ENV' ) && ( WP_ENV === 'development') ) ?
		"<strong>" . $this_pub->pub_title . " (ID: " . $this_pub->post_title . ")" . "</strong>" :
		"<strong>" . $this_pub->pub_title . "</strong>" ;
	
	// default url to use for publication title
	$dflt_url = ( ( !empty( $this_pub->adsabs_url ) ) ? $this_pub->adsabs_url : 
				( ( !empty( $this_pub->doi_url ) ) ? $this_pub->doi_url : 
				( ( !empty( $this_pub->arxiv_url ) ) ? $this_pub->arxiv_url : 
				false ) ) ) ;
	$result .= ( $dflt_url ) ? "<a target='_blank' href='$dflt_url' >" . $this_title . "</a>" : $this_title ;
	
	$result .=  '<br />' . $this_pub->authors .  '. ' ;
	$result .= ( empty( $this_pub->journal_reference ) ) ? '<em>' . $this_pub->status . '</em>' : $this_pub->journal_reference ;
	$result .= ( empty($this_pub->adsabs ) ) ? '' : "; <a href='" . $this_pub->adsabs_url . "' target='_blank'>adsabs:" . $this_pub->adsabs . "</a>" ;
	$result .= ( empty($this_pub->doi ) ) ? '' : "; <a href='" . $this_pub->doi_url . "' target='_blank'>doi:" . $this_pub->doi . "</a>" ;
	$result .= ( empty($this_pub->arxiv_url ) ) ? '' : "; <a href='" . $this_pub->arxiv_url . "' target='_blank'>arXiv:" . $this_pub->arxiv . "</a>" ;
		
	return $result;

}

// Delete this CPT post
function idies_delete_cpt( $item, $key ) {
    return wp_delete_post( $item->ID , true );	
}

/*
 * Show some debug values in preformatted style.
 */
function idies_debug( $var ) {
        if ( WP_DEBUG ) :

                echo "<PRE>";

                if (is_array($var)) :
                        print_r($var);
                elseif (is_object($var)) :
                        var_dump($var);
                else :
                        print($var);
                endif;

                echo "</PRE>";

        endif;
        return;
}


?>