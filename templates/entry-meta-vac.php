<?php 
global $post;
global $cas_root;
/*/
$cas_root = ( defined( 'WP_ENV' ) && ( 'development' == WP_ENV ) ) ? 
	"http://skyserver.sdss.org/dr13/en/help/browser/browser.aspx#&&history=description+" :
	"http://skyserver.sdss.org/dr14/en/help/browser/browser.aspx#&&history=description+" ;
/*/
$cas_root = "http://skyserver.sdss.org/public/en/help/browser/browser.aspx#&&history=description+" :

// Split up DR and add extra css class to the latest.
$data_release_tags = array_filter( $post->{'data_releases'} , 'strlen' );
uasort ( $data_release_tags , 
	function( $a , $b ){
		return ( preg_replace("/[^0-9]/", "", $a) < preg_replace("/[^0-9]/", "", $b ) );
	}
);
array_walk( $data_release_tags , 
	function( &$value , &$key) {
		$value = ( defined( 'DATA_RELEASE' ) && 
					( preg_replace("/[^0-9]/", "", DATA_RELEASE ) == preg_replace("/[^0-9]/", "", $value) ) ) ?
			'<div class="vac-tag vac-dr vac-dr-latest">' . $value . '</div>' :
			'<div class="vac-tag vac-dr">' . $value . '</div>' ;
	}
);
	
$data_release_tags = implode('',$data_release_tags);

$survey_tags = ( empty( $post->{'survey'} ) ) ? "" : '<div class="vac-tag vac-survey">' . $post->{'survey'} . '</div>';

$object_tags = array_filter( $post->{'vac_objects'} , 'strlen' );
array_walk( $object_tags , 
	function( &$value , &$key) {
		$value = '<div class="vac-tag vac-object">' . $value . '</div>' ;
	} );
$object_tags = implode('',$object_tags);

$post_modified = ( defined( 'WP_ENV' ) && ( WP_ENV === 'development') ) ?
	'<div class="vac-modified">Last Modified: ' . $post->{'vac_modified'} . '</div>' :
	'';

// Single VAC
if ( is_single( $post->ID ) ) {
	
	// Has CAS & CAS Tables
	$cas_tag = '';
	$cas_table_tag = empty( $post->{'cas_tables'} ) ? array() : $post->{'cas_tables'} ;
	array_walk( $cas_table_tag , 
		function( &$value , &$key ) {
			global $cas_root;
			$cas_url = $cas_root . $value . "+U";
			$value = '<a href="' . $cas_url . '" target="_blank"><div class="vac-tag vac-table">CAS: ' . $value . '</div></a>' ;
		});
	$cas_table_tag = implode('',$cas_table_tag);
	
	// Show all Authors
	$the_authors = empty( $post->{'vac_authors'} ) ?
		"" : 
		"<div class='vac-authors'>" . $post->{'vac_authors'} . "</div>";
		
// VAC ARCHIVE
} else {
	
	// Has CAS & CAS Tables
	$cas_tag = ( $post->{'includes_cas'} ) ? '<div class="vac-tag vac-cas">CAS</div>' : '<div class="vac-tag vac-cas vac-no-cas">No CAS</div>' ;
	$cas_table_tag = '';
	
	// Truncate Authors on VAC Archive, Filter for empty string
	$the_authors = array_filter( explode( "," , $post->{'vac_authors'} ), 'strlen' );
	switch ( count( $the_authors ) ) {
		case 0:	
			$the_authors = "";
		case 1:	
			$the_authors = "<div class='vac-authors'>" . $the_authors[0] . "</div>";
			break;
		case 2:	
			$the_authors = "<div class='vac-authors'>" . $the_authors[0] . ", " . $the_authors[1] . "</div>";
			break;
		default:
			$the_authors = "<div class='vac-authors'>" . $the_authors[0] . " et al." . "</div>";
	}

}

echo "<div class='vac-tags'>";
echo $data_release_tags;
echo $survey_tags; 
echo $cas_tag; 
echo $cas_table_tag;
echo $object_tags;
echo "</div>";
echo $the_authors; 
echo "<div class='clearfix'></div>";
?>