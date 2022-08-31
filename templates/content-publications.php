<?php 
the_content(); 

$publications_data_json = @file_get_contents(  PATH_JSON . 'publications.json' );
$publications_data = json_decode( $publications_data_json, true );
echo '<div class="sdss-wrapper">';

// get the data from options 
/*$option  = get_option( 'sync_json_options' , false );
$error_level="info";
$msg=false;

if ( $option !== false ) {

	if ( $option[0][ "publications-updating" ] == "true" ){
		
		$msg = "Publications are currently being updated. Please try again in a few minutes.";
		$error_level="warning";
		
	} else {
		$publications_data = get_option( 'sdss_publications' );
	
		// VACs as Options
		$the_pubs =  get_pub_option_list( -1 );
		if ( $the_pubs === false ) {
			$msg = "No publications found.";
			$error_level='danger';
		} else {
			echo $the_pubs;
			echo "<p class='modified'>Last modified: " . $option[0][ "publications-modified" ] . "</p>";
		}
	}
}
if ( $msg ) echo "<div class='alert alert-$error_level'>" . $msg . "</div>";


$the_pubs =  get_pub_list( -1 );
if ( $the_pubs === false ) {
	echo "<div class='label label-warning'>No publications found</div>";
	return;
} else {
	echo $the_pubs;
}

// VACs as Options
if ( empty( $publications_data ) ) {
	echo "<div class='label label-warning'>No publications found</div>";
	return;
}*/

echo "<ul class='fa-ul'>";
foreach ( $publications_data['publications'] as $this_pub ) :  
	// default url to use for publication title
	$dflt_url = ( !empty( $this_pub[ 'adsabs_url' ] ) ) ? $this_pub[ 'adsabs_url' ] : 
				(( !empty( $this_pub[ 'doi_url' ] ) ) ? $this_pub[ 'doi_url' ] : 
				(( !empty( $this_pub[ 'arxiv_url' ] ) ) ? $this_pub[ 'arxiv_url' ] : false ));
	echo "<li><i class='fa-li fa fa-book'></i>";
	if ( $dflt_url ) echo "<a target='_blank' href='$dflt_url' >";
	echo "<strong>" . $this_pub[ 'title' ] . "</strong>";
	if ( $dflt_url ) echo "</a>";
	echo '<br />' . $this_pub[ 'authors' ] .  '. ' ;
	if ( $this_pub[ 'journal_reference' ]) {
		echo $this_pub[ 'journal_reference' ];
	} else {
		echo '<em>' . $this_pub[ 'status' ] . '</em>';
	}	
	if ( !empty($this_pub[ 'adsabs' ] ) ) echo "; <a href='" . $this_pub[ 'adsabs_url' ] . "' target='_blank'>adsabs:" . $this_pub[ 'adsabs' ] . "</a>";
	if ( !empty($this_pub[ 'doi' ] ))  echo "; <a href='" . $this_pub[ 'doi_url' ] . "' target='_blank'>doi:" . $this_pub[ 'doi' ] . "</a>";
	if ( !empty($this_pub[ 'arxiv_url' ] ) ) echo "; <a href='" . $this_pub[ 'arxiv_url' ] . "' target='_blank'>arXiv:" . $this_pub[ 'arxiv' ] . "</a>";
	echo '.</li>';
endforeach; 
echo '</ul>';
echo "<p>Last modified: ".$publications_data['modified']."</p>";
echo '</div>';
?>